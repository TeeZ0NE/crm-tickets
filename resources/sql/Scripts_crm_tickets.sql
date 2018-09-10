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
AND (sact.lastreply between  DATE_FORMAT('2018-07-01' ,'%Y-%m-01') AND DATE_FORMAT('2018-07-31','%Y-%m-%d') )
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
AND (sact.lastreply between  DATE_FORMAT('2018-07-01' ,'%Y-%m-%d') AND DATE_FORMAT('2018-07-31','%Y-%m-%d') )
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

INSERT INTO sysadmin_niks(service_id,admin_nik,user_id) VALUES(1,'Boryan',2);