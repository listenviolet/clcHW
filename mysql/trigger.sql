	
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

grant execute on procedure clcHW.resubHW to 'admin'@'localhost';
flush privileges;
------------------
drop procedure updateHW;/
create procedure updateHW(
	in id char(15),
	in stuID int,
	in hwID int,
	in stu_hw varchar(200)
)
begin
	declare existed_id int;
	update stu_hw set active=0 where stu_hw.stu_id=stuID and stu_hw.hw_id=hwID and active=1;
	insert into stu_hw(id,stu_id,hw_id,stu_hw) values(id,stuID,hwID,stu_hw);
end;

grant all on procedure clcHW.updateHW to 'admin'@'localhost';/
flush privileges;/

drop procedure updateHW;/
create procedure updateHW(
	in id char(15),
	in stuID int,
	in hwID int,
	in stu_hw varchar(200)
)
begin
	declare old_id char(15);
	declare finish int default 0;
	declare cur_id cursor for select id from stu_hw where stu_hw.stu_id=stuID and stu_hw.hw_id=hwID and active=1;
	declare continue handler for not found set finish = 1;
	open cur_id;
	update_hw: loop
		fetch cur_id into old_id;
		if finish=1 then leave update_hw;
		end if;
		update stu_hw set active=0 where id=old_id;
	end loop update_hw;
	close cur_id;
	insert into stu_hw(id,stu_id,hw_id,stu_hw) values(id,stuID,hwID,stu_hw);
end;/

	--update stu_hw set active=0 where stu_hw.stu_id=stuID and stu_hw.hw_id=hwID and active=1;
	--insert into stu_hw(id,stu_id,hw_id,stu_hw) values(id,stuID,hwID,stu_hw);
--end;