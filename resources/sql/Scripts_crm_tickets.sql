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

SELECT name,COUNT(DISTINCT ticket_id)AS tickets_count, COUNT(lastreply) AS replies_count,SUM(time_uses)  AS time_sum 
FROM sysadmin_activities
LEFT JOIN sysadmin_niks AS sniks ON sniks.id=sysadmin_niks_id
LEFT JOIN users ON users.id=user_id
WHERE sysadmin_niks_id IN (
	SELECT sniks.id FROM users
	LEFT JOIN sysadmin_niks AS sniks ON sniks.user_id=users.id
WHERE users.id=2
)
AND (lastreply between  DATE_FORMAT(NOW() ,'%Y-%m-01') AND NOW() )
GROUP BY (name) ORDER BY tickets_count DESC, replies_count DESC;

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


