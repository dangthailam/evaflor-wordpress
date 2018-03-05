/*
Table for lottery game
*/
create table lottery(id bigint(20) NOT NULL auto_increment primary key, clientName varchar(255) NOT NULL, email varchar(255) NOT NULL, zipCode varchar(255) NOT NULL, lotteryNumber int NOT NULL, lotteryDate DATE);

/* History of lottery date and winner info */
create table lotteryDate(id bigint(20) NOT NULL auto_increment primary key, lotteryDate DATE, isFinished varchar(255) NOT NULL, winnerId bigint(20));
/*
Insert test data
*/
insert into lottery (clientName, email, zipCode, lotteryNumber, lotteryDate) values ("clientToTo", "evaflor@vac.com", "75001", 4354, "2017-10-07");

