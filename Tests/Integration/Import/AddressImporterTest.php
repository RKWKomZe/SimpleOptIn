<?php
namespace Madj2k\SimpleConsent\Tests\Integration\Import;

/*
 * This file is part of the TYPO3 CMS project.
 *
 * It is free software; you can redistribute it and/or modify it under
 * the terms of the GNU General Public License, either version 2
 * of the License, or any later version.
 *
 * For the full copyright and license information, please read the
 * LICENSE.txt file that was distributed with this source code.
 *
 * The TYPO3 project - inspiring people to share!
 *
 */


use League\Csv\Reader;
use Madj2k\SimpleConsent\Import\AddressImporter;
use Nimut\TestingFramework\TestCase\FunctionalTestCase;
use Madj2k\CoreExtended\Utility\FrontendSimulatorUtility;
use Madj2k\FeRegister\Domain\Model\FrontendUser;
use Madj2k\FeRegister\Domain\Model\GuestUser;
use Madj2k\FeRegister\Domain\Model\OptIn;
use Madj2k\FeRegister\Domain\Repository\BackendUserRepository;
use Madj2k\FeRegister\Domain\Repository\FrontendUserGroupRepository;
use Madj2k\FeRegister\Domain\Repository\FrontendUserRepository;
use Madj2k\FeRegister\Domain\Repository\OptInRepository;
use Madj2k\FeRegister\Domain\Repository\ConsentRepository;
use Madj2k\FeRegister\Registration\FrontendUserRegistration;
use Madj2k\FeRegister\Utility\FrontendUserSessionUtility;
use Madj2k\SoapApi\Data\DataHandler;
use TYPO3\CMS\Core\Utility\GeneralUtility;
use TYPO3\CMS\Extbase\Mvc\Request;
use TYPO3\CMS\Extbase\Object\ObjectManager;
use TYPO3\CMS\Extbase\Persistence\Generic\PersistenceManager;

/**
 * AddressImporterTest
 *
 * @author Steffen Krogel <developer@steffenkroggel.de>
 * @copyright Steffen Kroggel
 * @package Madj2k_SoapApi
 * @license http://www.gnu.org/licenses/gpl.html GNU General Public License, version 3 or later
 */
class AddressImporterTest extends FunctionalTestCase
{
    /**
     * @const
     */
    const FIXTURE_PATH = __DIR__ . '/AddressImporterTest/Fixtures';


    /**
     * @var string[]
     */
    protected $testExtensionsToLoad = [
        'typo3conf/ext/simple_consent',
        'typo3conf/ext/core_extended',
    ];


    /**
     * @var string[]
     */
    protected $coreExtensionsToLoad = [ ];


    /**
     * @var \TYPO3\CMS\Extbase\Object\ObjectManager|null
     */
    private ?ObjectManager $objectManager = null;


    /**
     * @var \Madj2k\SimpleConsent\Import\AddressImporter|null
     */
    private ?AddressImporter $fixture = null;


    /**
     * Setup
     * @throws \Exception
     */
    protected function setUp(): void
    {
        parent::setUp();
        $this->importDataSet(self::FIXTURE_PATH . '/Database/Global.xml');

        $this->setUpFrontendRootPage(
            1,
            [
                'EXT:core_extended/Configuration/TypoScript/setup.typoscript',
                'EXT:core_extended/Configuration/TypoScript/constants.typoscript',
                self::FIXTURE_PATH . '/Frontend/Configuration/Rootpage.typoscript',
            ],
            ['example.com' => self::FIXTURE_PATH .  '/Frontend/Configuration/config.yaml']
        );

        /** @var \TYPO3\CMS\Extbase\Object\ObjectManager $objectManager */
        $this->objectManager = GeneralUtility::makeInstance(ObjectManager::class);

        /** \Madj2k\SimpleConsent\Import\AddressImporter $fixture */
        $this->fixture = $this->objectManager->get(
            AddressImporter::class,
            self::FIXTURE_PATH . '/Files/TestData.csv'
        );
    }


    #==============================================================================

    /**
     * @test
     * @throws \Exception
     */
    public function constructsLoadsReader()
    {
        /**
         * Scenario:
         *
         * When the method is called
         * Then the CSV-Reader is initialized
         */

        self::assertInstanceOf(Reader::class, $this->fixture->getReader());
    }


    #==============================================================================

    /**
     * @test
     * @throws \Exception
     */
    public function getImportableColumnsReturnsAllowedColumnsOnly()
    {
        /**
         * Scenario:
         *
         * When the method is called
         * Then an array is returned
         * Then this array contains all importable columns
         * Then the system-relevant exclude columns are not returned
         */

        $result = $this->fixture->getImportableColumns();
        $expectedKeys = [
            'gender', 'title', 'first_name', 'last_name', 'company', 'address', 'zip', 'city', 'phone', 'email'
        ];

        self::assertIsArray($result);
        self::assertCount(count($expectedKeys), $result);
        self::assertEquals($expectedKeys, $result);

    }

    #==============================================================================

    /**
     * @test
     * @throws \Exception
     */
    public function getHeaderRawReturnsUnfilteredArrayOfColumnNamesFromCsv()
    {
        /**
         * Scenario:
         *
         * When the method is called
         * Then an array is returned
         * Then this array contains all column-names from the CSV
         */

        $result = $this->fixture->getHeaderRaw();
        $expectedKeys = [
            'uid', 'gender', 'title', 'first_name', 'last_name', 'company', 'address', 'city',
            'zip', 'useless', 'phone', 'email', 'hash', 'status'
        ];

        self::assertIsArray($result);
        self::assertCount(count($expectedKeys), $result);
        self::assertEquals($expectedKeys, $result);

    }

    #==============================================================================

    /**
     * @test
     * @throws \Exception
     */
    public function getHeaderReturnsFilteredArrayOfColumnNamesFromCsv()
    {
        /**
         * Scenario:
         *
         * When the method is called
         * Then an array is returned
         * Then this array contains only the importable column-names from the CSV
         * Then this array keeps the original numeric array-keys for the row-numbers
         */

        $result = $this->fixture->getHeader();
        $expectedKeys = [
            1 => 'gender',
            2 => 'title',
            3 => 'first_name',
            4 => 'last_name',
            5 => 'company',
            6 => 'address',
            7 => 'city',
            8 => 'zip',
            10 => 'phone',
            11 => 'email'
        ];

        self::assertIsArray($result);
        self::assertCount(count($expectedKeys), $result);
        self::assertEquals($expectedKeys, $result);

    }

    #==============================================================================


    /**
     * @test
     * @throws \Exception
     */
    public function getRecordsRawReturnsUnfilteredArrayOfRecords()
    {
        /**
         * Scenario:
         *
         * When the method is called
         * Then an array is returned
         * Then this array contains all records
         * Then the header is not returned
         * Then each array-row contains all column-names of the CSV
         */

        $result = $this->fixture->getRecordsRaw();
        $expectedKeys = [
            'uid', 'gender', 'title', 'first_name', 'last_name', 'company', 'address', 'city', 'zip', 'useless',
            'phone', 'email', 'hash', 'status'
        ];

        self::assertIsArray($result);
        self::assertCount(4, $result);

        self::assertEmpty($result[0]);

        self::assertCount(count($expectedKeys), $result[1]);
        self::assertEquals($expectedKeys, array_keys($result[1]));

        self::assertCount(count($expectedKeys), $result[2]);
        self::assertEquals($expectedKeys, array_keys($result[2]));

        self::assertCount(count($expectedKeys), $result[3]);
        self::assertEquals($expectedKeys, array_keys($result[3]));

        self::assertCount(count($expectedKeys), $result[4]);
        self::assertEquals($expectedKeys, array_keys($result[4]));

    }

    #==============================================================================

    /**
     * @test
     * @throws \Exception
     */
    public function getRecordsReturnsFilteredArrayOfRecords()
    {
        /**
         * Scenario:
         *
         * When the method is called
         * Then an array is returned
         * Then this array contains all records
         * Then the header is not returned
         * Then each array-row contains an array which contains only the importable fields per record
         */

        $result = $this->fixture->getRecords();
        $expectedKeys = [
            'gender', 'title', 'first_name', 'last_name', 'company', 'address', 'city', 'zip',
            'phone', 'email'
        ];

        self::assertIsArray($result);
        self::assertCount(4, $result);

        self::assertEmpty($result[0]);

        self::assertCount(count($expectedKeys), $result[1]);
        self::assertEquals($expectedKeys, array_keys($result[1]));

        self::assertCount(count($expectedKeys), $result[2]);
        self::assertEquals($expectedKeys, array_keys($result[2]));

        self::assertCount(count($expectedKeys), $result[3]);
        self::assertEquals($expectedKeys, array_keys($result[3]));

        self::assertCount(count($expectedKeys), $result[4]);
        self::assertEquals($expectedKeys, array_keys($result[4]));
    }

    /**
     * @test
     * @throws \Exception
     */
    public function getRecordsTransformsCoding()
    {
        /**
         * Scenario:
         *
         * When the method is called
         * Then an array is returned
         * Then this array contains all records
         * Then the german umlauts are coded correctly
         */

        $result = $this->fixture->getRecords();

        self::assertIsArray($result);
        self::assertCount(4, $result);

        self::assertEquals('BÜNDNIS 90/DIE GRÜNEN', $result[2]['company']);

    }

    #==============================================================================

    /**
     * TearDown
     */
    protected function teardown(): void
    {
        FrontendSimulatorUtility::resetFrontendEnvironment();

        parent::tearDown();
    }

}
