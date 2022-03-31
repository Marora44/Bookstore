use bookstore;
SET foreign_key_checks = 0;
drop table if exists Author;
drop table if exists Publisher;
drop table if exists Book;
drop table if exists Review;
drop table if exists User;
drop table if exists AccountHolder;
drop table if exists BookOrder;
drop table if exists AddressInfo;
drop table if exists PaymentInfo;
drop table if exists Buys;
drop table if exists Ships;
drop table if exists Pays;
drop table if exists StoredShip;
drop table if exists StoredPay;
drop table if exists ShippingMethods;
SET foreign_key_checks = 1; 
  
create table Author (
  id int auto_increment,
  firstname varchar(30),
  lastname varchar(30),
  primary key(id)
);

create table Publisher (
  id int auto_increment,
  password varchar(20),
  name varchar(30),
  primary key(id, name)
);
 
create table Book (
  isbn char(13),
  title varchar(50),
  authorID int,
  genre varchar(20),
  price decimal(10,2),
  isDigital boolean,      
  isPhysical boolean,     
  pubID int,
  quantity int,
  primary key(isbn),
  foreign key (pubID) references Publisher(id),
  foreign key (authorID) references Author(id)
);

create table User (
  id int auto_increment,
  primary key(id)
);
 
create table AccountHolder (
  username varchar(20),
  password varchar(20),
  isMember boolean,
  firstname varchar(30),
  lastname varchar(30),
  userID int,
  primary key(username),
  foreign key (userID) references User(id)
);

create table Review (
  id int auto_increment,
  isbn char(13),
  rtext varchar(500),
  rating int,
  username varchar(20),
  primary key(id),
  foreign key (isbn) references Book(isbn),
  foreign key (username) references AccountHolder(username),
  CHECK (rating >= 0 AND rating <= 5)
);

create table BookOrder (
  id int, 
  isbn char(13),
  orderDate datetime,
  quantity int,
  userID int,
  isDigital boolean,
  isPlaced boolean,
  primary key(id, isbn, isDigital),
  foreign key (isbn) references Book(isbn),
  foreign key (userID) references User(id)
);

create table AddressInfo (
  id int auto_increment,
  street varchar(30),
  city varchar(30),
  state varchar(20),
  zip char(5),
  primary key(id)
);
create table PaymentInfo (
  id int auto_increment,
  cc varchar(16),
  cvv char(3),
  expDate char(5),
  billingID int,
  primary key(id),
  foreign key (billingID) references AddressInfo(id)
);

create table ShippingMethods(
  method varchar(20),
  price decimal(10,2),
  primary key(method)
);

create table Buys (
  userID int,
  orderID int,
  foreign key (userID) references User(id),
  foreign key (orderID) references BookOrder(id)
);

create table Ships (
  orderID int,
  addressID int,
  shippingMethod varchar(20),
  foreign key (orderID) references BookOrder(id),
  foreign key (addressID) references AddressInfo(id),
  foreign key (shippingMethod) references ShippingMethods(method)
);

create table Pays (
  orderID int,
  paymentID int,
  foreign key (orderID) references BookOrder(id),
  foreign key (paymentID) references PaymentInfo(id)
);

create table StoredShip (
  userID int,
  addressID int,
  foreign key (userID) references User(id),
  foreign key (addressID) references AddressInfo(id)
);

create table StoredPay (
  userID int,
  paymentID int,
  foreign key (userID) references User(id),
  foreign key (paymentID) references PaymentInfo(id)
);

INSERT INTO User VALUES(-1);
INSERT INTO USER VALUES();
INSERT INTO USER VALUES();
INSERT INTO USER VALUES();
INSERT INTO USER VALUES();

INSERT INTO AccountHolder VALUES("admin","admin",1,"Database","Manager",-1);
INSERT INTO AccountHolder VALUES("john21","12345",1,"John","Doe",1);
INSERT INTO AccountHolder VALUES("jane21","12345",1,"Jane","Doe",2);
INSERT INTO AccountHolder VALUES("marora44","12345",0,"Mundeep","Arora",3);
INSERT INTO AccountHolder VALUES("bg123","12345",0,"Brent","Garey",3);

INSERT INTO Author(firstname,lastname) VALUES("Dr","Suess");
INSERT INTO Author(firstname,lastname) VALUES("Lemony","Snicket");
INSERT INTO Author(firstname,lastname) VALUES("JK","Rowling");
INSERT INTO Author(firstname,lastname) VALUES("Rick","Riordan");
INSERT INTO Author(firstname,lastname) VALUES("John","Green");

INSERT INTO Publisher(password,name) VALUES("12345","Pub1");
INSERT INTO Publisher(password,name) VALUES("12345","Pub2");
INSERT INTO Publisher(password,name) VALUES("12345","Pub3");
INSERT INTO Publisher(password,name) VALUES("12345","Pub4");
INSERT INTO Publisher(password,name) VALUES("12345","Pub5");

INSERT INTO Book(isbn,price) VALUES("become_member",30);
INSERT INTO Book VALUES("0000000000001","The cat In the Hat",1,"Children",50,1,1,1,30);
INSERT INTO Book VALUES("0000000000002","A Series of Unfortunate Events",2,"Fiction",10,1,0,2,10);
INSERT INTO Book VALUES("0000000000003","Harry Potter",3,"Fiction",20,1,1,3,40);
INSERT INTO Book VALUES("0000000000004","Percy Jackson",4,"Fiction",15,1,1,4,50);

INSERT INTO Review(isbn,rtext,rating,username) VALUES ("0000000000001","good book", 5, "marora44");
INSERT INTO Review(isbn,rtext,rating,username) VALUES ("0000000000001","bad book", 1, "john21");
INSERT INTO Review(isbn,rtext,rating,username) VALUES ("0000000000002","good book", 5, "bg123");
INSERT INTO Review(isbn,rtext,rating,username) VALUES ("0000000000003","good book", 5, "marora44");
INSERT INTO Review(isbn,rtext,rating,username) VALUES ("0000000000004","good book", 5, "marora44");

INSERT INTO BookOrder VALUES(1, "0000000000001", NOW(), 3, 3, 0, 1);
INSERT INTO BookOrder VALUES(1, "0000000000002", NOW(), 2, 3, 1, 1);
INSERT INTO BookOrder VALUES(2, "0000000000001", NOW(), 7, 1, 0, 0);
INSERT INTO BookOrder VALUES(3, "0000000000001", NOW(), 5, 2, 1, 0);
INSERT INTO BookOrder VALUES(4, "0000000000001", NOW(), 5, 2, 1, 1);

INSERT INTO ShippingMethods VALUES("fast", 10);
INSERT INTO ShippingMethods VALUES("slow", 5);
INSERT INTO ShippingMethods VALUES("member", 0);