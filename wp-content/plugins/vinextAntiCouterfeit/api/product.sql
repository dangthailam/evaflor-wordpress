/*Search product from tag*/
select productId from adntest.product_tags where tag = {tagId}

/*Create product and tags */
create table product (id bigint(20) NOT NULL auto_increment primary key, productId varchar(255)
NOT NULL, productDes text(65353));

create table product_tags(productId varchar(255) NOT NULL, tag varchar(255) NOT NULL);

/*insert product to test*/
insert into product (productId, productDes) values ('test1', 'This is Test1 Product'),('test2', 'This is Test2 Product'),
('test3', 'This is Test3 Product'), ('test5', 'This is Test5 Product'), ('test4', 'This is Test4 Product');

insert into product_tags (productId, tag) values ('test1', '1111'), ('test1', '2222'),
('test1', '6666'), ('test2', '6565'), ('test2', '5989'), ('test3', '5895'), ('test3', '9412'),
('test4', '8755'), ('test4', '1202'), ('test5', '5124'), ('test5', '6110');

create table visitor_geolocation(id bigint(20) NOT NULL auto_increment primary key, 
productId varchar(255),
isValid varchar(10), 
city varchar(60), 
country varchar(60), 
IP varchar(30),
latitude varchar(30),
longitude varchar(30),
accuracyRadius varchar(60),
sessionid varchar(255)
);

create table countdown(id bigint(20) NOT NULL auto_increment primary key,
productId varchar(255),
dateCountdown DATE
);

create table temporalLocation(id bigint(20) NOT NULL auto_increment primary key,
latitude varchar(255),
longitude varchar(255),
sessionid varchar(255)
);

insert into visitor_geolocation(productId, isValid, city, country, IP, latitude, longitude, accuracyRadius) values ('ABCDABCDABCDGH','NO', 'Clamart', 'France (FR)', '85.171.233.237', '48.803', '2.2669', '1km');
