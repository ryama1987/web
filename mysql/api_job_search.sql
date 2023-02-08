
CREATE TABLE `api_job_search` (
`Id` varchar(80) PRIMARY KEY,
`Name` varchar(255) UNIQUE,
`Status__c` varchar(6) DEFAULT NULL,
`Catch__c` varchar(255) DEFAULT NULL,
`PositionInner__c` varchar(255) DEFAULT NULL,
`BusinessContents__c` mediumtext DEFAULT NULL,
`RequiredQualification__c` mediumtext DEFAULT NULL,
`Qualification__c` mediumtext DEFAULT NULL,
`ApplyRequirement__c` mediumtext DEFAULT NULL,
`Prefecture1__c` varchar(8) DEFAULT NULL,
`City1__c` varchar(100) DEFAULT NULL,
`Station__c` varchar(255) DEFAULT NULL,
`Transportation1__c` varchar(16) DEFAULT NULL,
`PublicWelfare__c` mediumtext DEFAULT NULL,
`PublicWelfareNote__c` varchar(255) DEFAULT NULL,
`CompanyCategory__c` varchar(128) DEFAULT NULL,
`Employment__c` varchar(64) DEFAULT NULL,
`EmploymentNote__c` varchar(255) DEFAULT NULL,
`FreeComment__c` longtext DEFAULT NULL,
`CompanyName__c` varchar(255) DEFAULT NULL,
`CompanyName_OpenFlag__c` boolean DEFAULT FALSE NOT NULL,
`Town1__c` varchar(255) DEFAULT NULL,
`BusinessNote2__c` varchar(1023) DEFAULT NULL,
`JobCharacteristic__c` varchar(1023) DEFAULT NULL,
`Occupation1_1__c` varchar(255) DEFAULT NULL,
`Occupation1_2__c` varchar(255) DEFAULT NULL,
`Occupation2_1__c` varchar(255) DEFAULT NULL,
`Occupation2_2__c` varchar(255) DEFAULT NULL,
`Occupation3_1__c` varchar(255) DEFAULT NULL,
`Occupation3_2__c` varchar(255) DEFAULT NULL,
`BusinessContents_zaitaku_A__c` varchar(64) DEFAULT NULL,
`BusinessContents_zaitaku_B__c` varchar(64) DEFAULT NULL,
`Industry1__c` varchar(64) DEFAULT NULL,
`Industry2__c` varchar(64) DEFAULT NULL,
`AnnualincomeLow__c` int DEFAULT NULL,
`AnnualincomeUpper__c` int DEFAULT NULL,
`HourlyWage1__c` int DEFAULT NULL,
`HourlyWage2__c` int DEFAULT NULL,
`LaborLawyer_Outsourcingfee__c` int DEFAULT NULL,
`OutsourcingfeeZaitaku__c` int DEFAULT NULL,
`Photo__c` varchar(255) DEFAULT NULL,
`Photo2__c` varchar(255) DEFAULT NULL,
`SelectedOption_Temp` mediumtext DEFAULT NULL,
`SelectedOption_TTP` mediumtext DEFAULT NULL,
`SelectedOption_Part` mediumtext DEFAULT NULL,
`SelectedOption_JP_Regular` mediumtext DEFAULT NULL,
`SelectedOption_JP_Part` mediumtext DEFAULT NULL,
`SelectedOption_JP_Subcontracting` mediumtext DEFAULT NULL,
`FulltextIndex_1` longtext DEFAULT NULL,
`FulltextIndex_2` longtext DEFAULT NULL,
`created` datetime DEFAULT CURRENT_TIMESTAMP NOT NULL,
`modified` datetime DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP NOT NULL
)ENGINE=InnoDB DEFAULT CHARSET=utf8mb4;

/* 全文検索のINDEXを貼る*/
ALTER TABLE api_job_search ADD FULLTEXT INDEX FulltextIndex_1 (FulltextIndex_1) WITH PARSER ngram;
SET GLOBAL innodb_ft_aux_table = 'jusnet_db/api_job_search';

/* INDEX削除 */
ALTER TABLE api_job_search DROP INDEX FulltextIndex_1;

/* 追加カラム */;
ALTER TABLE api_job_search ADD agent_flg int DEFAULT 0 NOT NULL;
ALTER TABLE api_job_search ADD thanks_screen_template varchar(255) DEFAULT NULL;

/* 順番変更 */;
ALTER TABLE api_job_search MODIFY agent_flg int AFTER seminar_id;
ALTER TABLE api_job_search MODIFY thanks_screen_template varchar(255) AFTER mail_template;

/* カラム削除 */;
ALTER TABLE api_job_search DROP COLUMN date;

/* テーブルの設定状況表示 */;
DESCRIBE api_job_search;

/* データを削除 */
DELETE FROM api_job_search WHERE id = 10;

/* テーブル自体削除 */
DROP TABLE **************;

/* テーブルを空にする */
TRUNCATE TABLE **************;


/* これが行かない場合は、
ALTER TABLE テーブル名 ADD FULLTEXT INDEX フルテキストインデックス名 (対象とするカラム名) WITH PARSER ngram　
を試してみること;
*/
SELECT Name, Status__c FROM api_job_search WHERE MATCH (FulltextIndex_1) AGAINST ('+東証プライム +経理' IN BOOLEAN MODE);
