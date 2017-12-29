CREATE TABLE `suber`.`oneTimeTrip` 
( `ID` INT NOT NULL , 
`City_departure` VARCHAR(10) NOT NULL , 
`Postcode_departure` INT(8) NOT NULL , 
`City_destination` INT(10) NOT NULL , 
`Postcode_destination` INT(8) NOT NULL , 
`Date` DATE NOT NULL , 
`Number_rider` INT NOT NULL DEFAULT '0' , 
`Number_offer` INT NOT NULL DEFAULT '0' , 
`driver` VARCHAR(12) NOT NULL ,
PRIMARY KEY (`ID`), 
Foreign key(`driver`) REFERENCES member(username),
CHECK (Number_offer <= Number_rider)
);



$prep = "UPDATE onetimetrip SET City_departure = '".$_POST['city_depart']."', Postcode_departure= '".$_POST['postcode_depart'].
		"', City_destination = '".$_POST['city_destin']."', Postcode_destination= '".$_POST['postcode_destin']."', Number_rider = '".$_POST['number_rider'].
		"', 'Date' = '".$_POST['dateOftrip']."', detail= '".$_POST['detail']."', driver= '".$_SESSION['user']['username']."';";
		
		
		
		
		
		create trigger Check_supCount before insert on UsedIn
for each row
begin
set @howmany=(SELECT count(*) from supplied where `part#` = NEW.`part#`);
if(howmany<1)
THEN
	SIGNAL SQLSTATE '45000'
	set MESSAGE_TEXT = 'no suppers ' || NEW.`part#` ||' supplied this part';
end if;
end;
