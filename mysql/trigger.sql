	
delimiter /
drop procedure if exists resubHW;
create procedure resubHW(
	in classID int,
	in stuID int
)
begin
	declare existed int;
	set existed=(select count(*) from stu_class where stu_class.class_id=classID and stu_class.stu_id=stuID);
	if existed>0 then
	update stu_class set active=0 where stu_class.class_id=classID and stu_class.stu_id=stuID;
	end if;
	insert into stu_class(class_id,stu_id) values(classID,stuID);
end;

set @classID=1,@stuID=3;
call resubHW(@classID,@stuID);

grant all on procedure clcHW.resubHW to 'admin'@'localhost';
flush privileges;