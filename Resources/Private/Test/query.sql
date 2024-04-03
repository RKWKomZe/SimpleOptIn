SELECT (SELECT COUNT(uid) FROM `tx_simpleconsent_domain_model_address` WHERE status > 0) as 'sent', (SELECT COUNT(uid) FROM `tx_simpleconsent_domain_model_address` WHERE status = 1) as 'reminderOpen',(SELECT COUNT(uid) FROM `tx_simpleconsent_domain_model_address` WHERE status >= 10) as 'reacted', (SELECT COUNT(uid) FROM `tx_simpleconsent_domain_model_address` WHERE status = 10) as 'consent', (SELECT COUNT(uid) FROM `tx_simpleconsent_domain_model_address` WHERE status = 11) as 'marketing', (SELECT COUNT(uid) FROM `tx_simpleconsent_domain_model_address` WHERE status = 20) as 'delete' FROM `tx_simpleconsent_domain_model_address` WHERE 1 = 1 LIMIT 1

UPDATE tx_simpleconsent_domain_model_address SET status = 20, tstamp = UNIX_TIMESTAMP(), comment='opt-out via email' WHERE LOWER(email) = LOWER('managementberatung@akh.de')

UPDATE tx_simpleconsent_domain_model_address SET status = 11, tstamp = UNIX_TIMESTAMP(), comment='opt-in via email' WHERE LOWER(email) = LOWER('silke.kraus@futurevision-consulting.de')



SELECT * FROM fe_users WHERE (fe_users.email IN(SELECT email FROM `tx_simpleconsent_domain_model_address` WHERE `status` = 20) OR fe_users.username IN(SELECT email FROM `tx_simpleconsent_domain_model_address` WHERE `status` = 20)) and fe_users.disable = 0 AND fe_users.deleted = 0
UPDATE fe_users SET deleted = 1, tstamp = UNIX_TIMESTAMP(), comment ='opt-out' WHERE (fe_users.email IN(SELECT email FROM `tx_simpleconsent_domain_model_address` WHERE `status` = 20) OR fe_users.username IN(SELECT email FROM `tx_simpleconsent_domain_model_address` WHERE `status` = 20)) and fe_users.disable = 0 AND fe_users.deleted = 0


SELECT * FROM `tx_rkwalerts_domain_model_alert` WHERE frontend_user IN(SELECT uid FROM fe_users WHERE deleted = 1) AND deleted = 0
UPDATE `tx_rkwalerts_domain_model_alert` SET deleted = 1, tstamp = UNIX_TIMESTAMP() WHERE frontend_user IN(SELECT uid FROM fe_users WHERE deleted = 1) AND deleted = 0

SELECT * FROM `tx_rkwcanvas_domain_model_canvas` WHERE frontend_user IN(SELECT uid FROM fe_users WHERE deleted = 1) AND deleted = 0
UPDATE `tx_rkwcanvas_domain_model_canvas` SET deleted = 1 WHERE frontend_user IN(SELECT uid FROM fe_users WHERE deleted = 1) AND deleted = 0

SELECT * FROM `tx_rkwevents_domain_model_eventreservation` WHERE fe_user IN(SELECT uid FROM fe_users WHERE deleted = 1) AND deleted = 0
UPDATE `tx_rkwevents_domain_model_eventreservation` SET deleted = 1, tstamp = UNIX_TIMESTAMP() WHERE fe_user IN(SELECT uid FROM fe_users WHERE deleted = 1) AND deleted = 0

SELECT * FROM `tx_rkwshop_domain_model_order` WHERE frontend_user IN(SELECT uid FROM fe_users WHERE deleted = 1) AND deleted = 0
UPDATE `tx_rkwshop_domain_model_order` SET deleted = 1, tstamp = UNIX_TIMESTAMP() WHERE frontend_user IN(SELECT uid FROM fe_users WHERE deleted = 1) AND deleted = 0

SELECT * FROM `tx_rkwwebcheck_domain_model_checkresult` WHERE fe_user IN(SELECT uid FROM fe_users WHERE deleted = 1) AND deleted = 0
UPDATE `tx_rkwwebcheck_domain_model_checkresult` SET deleted = 1, tstamp = UNIX_TIMESTAMP() WHERE fe_user IN(SELECT uid FROM fe_users WHERE deleted = 1) AND deleted = 0

