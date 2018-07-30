-- Get count tickets and summary replies from start of month
select sysadmin_niks_id, COUNT(ticket_id), SUM(replies)
from sysadmin_activities
where (lastreply between  DATE_FORMAT(NOW() ,'%Y-%m-01') AND NOW() )
GROUP by sysadmin_niks_id;
-- test count of tickets by user
select ticket_id FROM sysadmin_activities
WHERE sysadmin_niks_id = 1;