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
  primary key(id)
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
  ccv int,
  foreign key (userID) references User(id),
  foreign key (paymentID) references PaymentInfo(id)
);