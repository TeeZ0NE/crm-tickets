-- Get count tickets and summary replies from start of month
select sysadmin_niks_id, COUNT(ticket_id), SUM(replies)
from sysadmin_activities
where (lastreply between  DATE_FORMAT(NOW() ,'%Y-%m-01') AND NOW() )
GROUP by sysadmin_niks_id;

-- #test count of tickets by user
/* snik_id
select name,sysadmin_niks_id,COUNT(DISTINCT ticket_id)AS tickets_count, COUNT(lastreply) AS replies_count,SUM(time_uses)  AS time_sum 
FROM sysadmin_activities
LEFT JOIN sysadmin_niks AS sniks ON sniks.id=sysadmin_niks_id
LEFT JOIN users ON users.id=user_id
WHERE sysadmin_niks_id IN (3,7)
AND (lastreply between  DATE_FORMAT(NOW() ,'%Y-%m-01') AND NOW() )
GROUP BY (sysadmin_niks_id);
*/

SELECT users.name,COUNT(DISTINCT ticket_id)AS tickets_count, COUNT(sact.lastreply) AS replies_count,SUM(time_uses)  AS time_sum, Sum(serv.compl)
FROM sysadmin_activities as sact
LEFT JOIN sysadmin_niks AS sniks ON sniks.id=sysadmin_niks_id
LEFT JOIN users ON users.id=user_id
join tickets ON tickets.id=ticket_id
RIGHT JOIN services as serv ON tickets.service_id=serv.id
WHERE sysadmin_niks_id IN (
	SELECT sniks.id FROM users
	LEFT JOIN sysadmin_niks AS sniks ON sniks.user_id=users.id
WHERE users.id=2
)
AND (sact.lastreply between  DATE_FORMAT(NOW() ,'%Y-%m-01') AND NOW() )
GROUP BY (users.name) ORDER BY tickets_count DESC, replies_count DESC;

-- get summary and comp count
SELECT u.name, COUNT(DISTINCT ticket_id) AS tickets_count, COUNT(sact.lastreply) AS replies_count ,SUM(time_uses), SUM(serv.compl)
-- SELECT u.name, ticket_id AS tickets_count, sact.lastreply AS replies_count ,time_uses, services.compl
FROM sysadmin_activities AS sact
LEFT JOIN sysadmin_niks AS sniks ON sniks.id=sysadmin_niks_id
LEFT JOIN users AS u ON u.id=user_id
join tickets ON tickets.id=ticket_id
RIGHT JOIN services as serv ON tickets.service_id=serv.id
WHERE sysadmin_niks_id IN (
	SELECT sniks.id FROM users
	LEFT JOIN sysadmin_niks AS sniks ON sniks.user_id=users.id
-- WHERE users.id=2
)
## curr month
-- AND (sact.lastreply between  DATE_FORMAT(NOW() ,'%Y-%m-01') AND NOW() )
# last month
AND (sact.lastreply between  DATE_FORMAT('2018-07-01' ,'%Y-%m-01') AND DATE_FORMAT('2018-09-31','%Y-%m-%d') )
GROUP BY (name) ORDER BY tickets_count DESC, replies_count DESC;
-- /summary and compl count
-- ORM
/*
 * DB::table('sysadmin_activities')->
 * select(DB::raw('sysadmin_niks_id, COUNT(DISTINCT ticket_id) AS tickets_count,COUNT(lastreply) AS replies_count, SUM(time_uses) AS time_sum'))->
 * whereBetween('lastreply',[\Carbon\Carbon::now()->startOfMonth(),\Carbon\Carbon::now()])->
 * where('sysadmin_niks_id',2)->
 * groupBy('sysadmin_niks_id')->
-- do whatever
*/
/*
 * 4 all users
 DB::table('sysadmin_activities')->
  select(DB::raw('name, COUNT(DISTINCT ticket_id) AS tickets_count,COUNT(lastreply) AS replies_count, SUM(time_uses) AS time_sum'))->
  leftJoin('sysadmin_niks as sniks','sniks.id','=','sysadmin_niks_id')->
  leftJoin('users','users.id','=','user_id')->
  whereIn('sysadmin_niks_id',function($q){
  $q->select(DB::raw('sniks.id from users left join sysadmin_niks as sniks on sniks.user_id=users.id'));
  })->
  whereBetween('lastreply',[\Carbon\Carbon::now()->startOfMonth(),\Carbon\Carbon::now()])->
  groupBy('name')->
  do whatever
  */

-- get sniks.id 4 user
SELECT sniks.id FROM users
LEFT JOIN sysadmin_niks AS sniks ON sniks.user_id=users.id
WHERE users.id=2;


-- /test count of tickets by user

UPDATE tickets SET user_assign_id=2 WHERE ticketid=10597 AND service_id=1;

SELECT COUNT(last_replier_nik_id) AS serv_1 FROM tickets
WHERE service_id=1
UNION SELECT COUNT(last_replier_nik_id) AS serv_2 FROM tickets
WHERE service_id=2;

-- get rate 4 users or curr user in current service beetwen in date
select services.name as service, COUNT(DISTINCT sact.ticket_id) AS tickets_count,COUNT(sact.lastreply) AS replies_count, u.name as user_name, SUM(sact.time_uses) AS sum_time, COUNT(DISTINCT ticket_id)*compl AS rate
from sysadmin_activities as sact
RIGHT JOIN tickets AS t ON sact.ticket_id=t.id
RIGHT JOIN services ON t.service_id=services.id
LEFT JOIN sysadmin_niks AS sniks ON sniks.id=sact.sysadmin_niks_id
LEFT JOIN users AS u ON u.id=sniks.user_id
where sact.sysadmin_niks_id in(select sniks.id from users LEFT JOIN sysadmin_niks as sniks on sniks.user_id=users.id 
where users.id=1
)
AND (sact.lastreply between  DATE_FORMAT('2018-07-01' ,'%Y-%m-%d') AND DATE_FORMAT('2018-09-31','%Y-%m-%d') )
AND services.id=1
GROUP BY service, user_name
ORDER BY tickets_count DESC, rate DESC;
-- /get rate 4 users or curr user in current service beetwen in date


UPDATE services SET compl=0.8 where services.id=1;

-- get rate without summing
select services.name as service, sact.ticket_id, sact.lastreply AS replies_count, u.name as user_name, sact.time_uses
from sysadmin_activities as sact
RIGHT JOIN tickets AS t ON sact.ticket_id=t.id
RIGHT JOIN services ON t.service_id=services.id
LEFT JOIN sysadmin_niks AS sniks ON sniks.id=sact.sysadmin_niks_id
LEFT JOIN users AS u ON u.id=sniks.user_id
where sact.sysadmin_niks_id in(select sniks.id from users LEFT JOIN sysadmin_niks as sniks on sniks.user_id=users.id 
where users.id=1
)
AND (sact.lastreply between  DATE_FORMAT('2018-07-01' ,'%Y-%m-%d') AND DATE_FORMAT('2018-07-31','%Y-%m-%d') )
AND services.id=1;
-- /get rate without summing

SELECT id from tickets WHERE service_id=1 LIMIT 1;

SELECT t.id FROM tickets AS t
WHERE t.is_closed=0 AND t.last_replier_nik_id=0 AND t.user_assign_id is NULL;

-- add and drop indexes
CREATE INDEX ticket_open ON tickets (is_closed);
DROP INDEX ticket_open ON tickets;

SELECT * from tickets USE INDEX(ticket_open);

DROP DATABASE crm_tickets_db;
CREATE DATABASE crm_tickets_db;
GRANT ALL PRIVILEGES ON crm_tickets_db.* TO 'boss'@'localhost';
flush PRIVILEGES;
UPDATE tickets SET user_assign_id=NULL where user_assign_id=9;


-- talbe like
CREATE DATABASE test;
USE test;
CREATE TABLE if NOT EXISTS users (
     id_1 MEDIUMINT NOT NULL AUTO_INCREMENT,
     name CHAR(30) NOT NULL,
     PRIMARY KEY (id_1)
);
INSERT INTO users (name) VALUES('bob'),('mike'),('george'),('steve');
SELECT * from test.users;
CREATE TABLE `like` (
    id_2 MEDIUMINT,
    id_3 MEDIUMINT,
FOREIGN KEY(id_2) REFERENCES users(id_1) ON UPDATE CASCADE on DELETE CASCADE,
FOREIGN KEY(id_3) REFERENCES users(id_1) on UPDATE CASCADE on DELETE CASCADE
); 
ALTER TABLE `like` 
ADD CONSTRAINT fk_id_2
FOREIGN KEY(id_2) REFERENCES users(id_1);
DROP TABLE `like`;

INSERT into `like` VALUES(1,2),(1,3),(2,3);
SELECT * FROM `like`;

SELECT u.name,id_3,id_1 FROM `like` as l
RIGHT JOIN users as u ON l.id_3=u.id_1
-- RIGHT JOIN users  ON l.id_2=users.id_1
WHERE l.id_3 is null;

SELECT * FROM users
LEFT JOIN `like` as l ON l.id_3=id_1
WHERE l.id_3 IS NULL; 
DROP DATABASE test;

INSERT INTO sysadmin_niks(service_id,admin_nik,user_id) VALUES(1,'Mooryan',1);

-- admin activities
INSERT INTO sysadmin_activities(sysadmin_niks_id,ticket_id,lastreply,time_uses) VALUES(2,146,'2018-08-27 08:45:31',15);
UPDATE sysadmin_activities SET `lastreply`='2018-09-27 08:45:31' WHERE ticket_id=146;
-- service month statistic
SELECT DISTINCT(t.id), COUNT(DISTINCT(sact.ticket_id)) as t_count,t.subject,s.name,t.ticketid, SUM(sact.time_uses) as sum_time FROM tickets as t
RIGHT JOIN sysadmin_activities AS sact ON sact.ticket_id=t.id
JOIN services AS s ON t.service_id=s.id
-- WHERE created_at>= subdate(curdate(),1)
-- yesterday
-- WHERE sact.lastreply>=SUBDATE(NOW(),2)
WHERE (t.created_at BETWEEN DATE_FORMAT('2018-10-01' ,'%Y-%m-%d') AND last_day('2018-10-01'))
-- AND t.service_id=1
GROUP BY t.id ORDER by sum_time;

SELECT s.id,s.name,COUNT(t.id),SUM(sact.time_uses) FROM services AS s
LEFT JOIN tickets AS t ON t.service_id=s.id
JOIN sysadmin_activities AS sact ON sact.ticket_id=t.id
WHERE s.id = 4 AND sact.lastreply BETWEEN
GROUP BY s.name;

-- service statistic by yesterday
SELECT DISTINCT(t.id),t.subject,s.name,t.ticketid, SUM(sact.time_uses) FROM tickets as t
LEFT JOIN sysadmin_activities AS sact ON sact.ticket_id=t.id
JOIN services AS s ON t.service_id=s.id
-- WHERE sact.lastreply= curdate()
AND t.service_id=4
GROUP BY t.id;


SELECT id FROM tickets WHERE created_at 
-- BETWEEN DATE_FORMAT(NOW() ,'%Y-%m-01') AND NOW();
-- BETWEEN DATE_FORMAT('2018-09-01','%Y-%m-%d') AND DATE_FORMAT('2018-09-30','%Y-%m-%d');
--  BETWEEN DATE_FORMAT('2018-09-01','%Y-%m-%d') AND NOW();
 BETWEEN DATE_FORMAT('2018-09-01','%Y-%m-%d') AND LAST_DAY('2018-09-01');
 
 SELECT last_day(now());
SELECT SUBDATE(CURDATE(),10);
SELECT SUBDATE(NOW(),1);
SELECT NOW() - INTERVAL 10 DAY;
SELECT id, lastreply FROM sysadmin_activities
WHERE lastreply BETWEEN DATE_FORMAT(CURDATE(),'%Y-%m-%d 00:00:00') and now();

SELECT id, lastreply FROM sysadmin_activities
WHERE lastreply BETWEEN DATE_FORMAT(subdate(CURDATE(),1),'%Y-%m-%d 00:00:00') and DATE_FORMAT(subdate(CURDATE(),1),'%Y-%m-%d 23:59:59');


SELECT SUM(tmp.sum_time_uses) AS total from (
SELECT sum(time_uses) sum_time_uses FROM sysadmin_activities WHERE  ticket_id=3) as tmp;

select NOW();
select DATE_FORMAT(CURDATE(),'%Y-%m-%d 00:00:00')
UNION
SELECT SUM(tmp.sum_time_uses) AS total from (
SELECT sum(time_uses) sum_time_uses FROM sysadmin_activities WHERE  ticket_id=3) as tmp;


SELECT SUM(time_uses) sum_time_uses FROM sysadmin_activities WHERE  ticket_id=3;

-- create table 4 emailing
CREATE TABLE IF NOT EXISTS emails(
id SMALLINT UNSIGNED AUTO_INCREMENT PRIMARY KEY,
email VARCHAR(86) NOT NULL UNIQUE,
is_main TINYINT(1) UNSIGNED DEFAULT 0
);

CREATE TABLE IF NOT EXISTS intervals(
id TINYINT UNSIGNED AUTO_INCREMENT PRIMARY KEY,
name VARCHAR(20) CHARACTER SET utf8 COLLATE utf8_general_ci NOT NULL UNIQUE,
url_attr VARCHAR(22) NOT NULL UNIQUE,
INDEX itreval_indx (name)
);

CREATE table if NOT EXISTS mailables(
id SMALLINT UNSIGNED AUTO_INCREMENT PRIMARY KEY,
service_id TINYINT UNSIGNED NOT NULL,
interval_id TINYINT UNSIGNED NOT NULL,
FOREIGN KEY(service_id) REFERENCES services(id) ON DELETE CASCADE ON UPDATE CASCADE,
FOREIGN KEY (interval_id) REFERENCES intervals(id) ON DELETE CASCADE on UPDATE CASCADE
);

CREATE TABLE IF NOT EXISTS mail_lists(
id SMALLINT UNSIGNED AUTO_INCREMENT PRIMARY KEY,
mailable_id SMALLINT UNSIGNED NOT NULL,
email_id SMALLINT UNSIGNED NOT NULL,
FOREIGN KEY(email_id) REFERENCES emails(id) ON UPDATE CASCADE on DELETE CASCADE,
FOREIGN KEY (mailable_id) REFERENCES mailables(id) on DELETE CASCADE ON UPDATE CASCADE
);
-- /create tables 4 emailngs

DROP TABLE intervals;

INSERT INTO mailables (service_id,mail_id,interval_id) VALUES (1,2,1);

SELECT * FROM intervals;

SELECT DISTINCT(s.name) as sname,
e.email
FROM mailables AS m
INNER JOIN services as s ON m.service_id=s.id
JOIN intervals as i on m.interval_id=i.id
JOIN emails as e on m.mail_id=e.id
where s.id=1
GROUP BY sname;

ALTER TABLE emails ADD is_main TINYINT(1) UNSIGNED DEFAULT 0;

SELECT s.name, e.email, i.name
FROM mailables AS m
LEFT JOIN services AS s ON s.id=m.service_id
LEFT JOIN emails AS e ON e.id=m.mail_id
LEFT JOIN intervals AS i ON i.id=m.interval_id;

SELECT service_id,interval_id FROM mailables
GROUP BY service_id,interval_id ORDER BY service_id;

SELECT mail_id FROM mailables
WHERE service_id = 1 AND interval_id=1;

INSERT INTO mailables(service_id,interval_id) VALUES(1,1),(1,2),(2,3);
INSERT INTO mail_lists(mailable_id,email_id) VALUES(1,1),(1,2),(3,1);

SELECT * FROM tickets USE INDEX(ticket_open);

INSERT INTO sysadmin_activities (sysadmin_niks_id, ticket_id, lastreply, time_uses) VALUES(2,16,'2018-10-09 11:00:21', 103);
DELETE FROM tickets WHERE id=2;

-- FK
select CONSTRAINT_NAME
from INFORMATION_SCHEMA.TABLE_CONSTRAINTS
where TABLE_NAME = 'tickets';

ALTER TABLE tickets DROP FOREIGN KEY tickets_service_id_foreign;

ALTER TABLE tickets ADD CONSTRAINT tickets_service_id_foreign
FOREIGN KEY(service_id) REFERENCES services(id) ON DELETE CASCADE ON UPDATE CASCADE;

-- /FK

DELETE FROM mailables;

-- remove old mailables posibilities
ALTER TABLE services ADD email VARCHAR(86) NULL;
ALTER TABLE emails DROP is_main;
-- /old mailable posibilities

UPDATE services SET email='endnet@ukr.net' WHERE name='adminvps';

SELECT t.id, t.ticketid, s.name, t.subject, sact.lastreply, sact.id as sact_id, sact.time_uses, snik.admin_nik, u.name as user_name
FROM tickets as t
RIGHT JOIN sysadmin_activities as sact ON sact.ticket_id=t.id
INNER JOIN sysadmin_niks as snik ON snik.id=sact.sysadmin_niks_id
LEFT JOIN users as u ON snik.user_id=u.id
LEFT JOIN services as s ON s.id=t.service_id
ORDER BY sact.lastreply;

CREATE USER 'boss'@'localhost' identified BY '1111';

GRANT ALL PRIVILEGES ON crm_tickets_db.* TO "boss"@"localhost";
flush PRIVILEGES;

select * from `sessions`;



CREATE USER 'boss2'@'localhost' IDENTIFIED WITH mysql_native_password BY '1111';
GRANT ALL PRIVILEGES ON crm_tickets_db.* TO 'boss2'@'localhost' WITH GRANT OPTION;
CREATE USER 'boss2'@'%' IDENTIFIED WITH mysql_native_password BY '1111';
GRANT ALL PRIVILEGES ON crm_tickets_db.* TO 'boss2'@'%' WITH GRANT OPTION;
GRANT ALL ON `crm_tickets_db`.* TO 'boss1'@'%' ;
FLUSH PRIVILEGES ;
