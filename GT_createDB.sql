DROP DATABASE IF EXISTS goodstracking;
CREATE DATABASE goodstracking;
USE goodstracking;
CREATE TABLE IF NOT EXISTS shops
	(
	shopID varchar(5) not null,
	shopname varchar(50) not null,
	seller_name varchar(30) not null,
	seller_surname varchar(30) not null,
    seller_idnumber varchar(13) not null,
	shop_username varchar(20) not null,
    shop_password varchar(20) not null,
    shop_email varchar(320) not null,
    seller_phoneno varchar(10) not null,
	primary key(shopID)
	);
CREATE TABLE IF NOT EXISTS orders
	(
	orderID int(10) not null auto_increment,
    shopID varchar(5) not null,
	custID varchar(5) not null,
    order_product varchar(50) not null,
    order_date date not null,
	order_amount int(3) not null,
    order_price int(6) not null,
    order_latest_status varchar(254) not null,
    latest_status_date date not null,
    order_tracking varchar(13),
    order_lot varchar(10) not null,
    primary key(orderID)
	);
CREATE TABLE IF NOT EXISTS customers
    (
    custID varchar(5) not null,
    cust_name varchar(30) not null,
    cust_surname varchar(30), 
    sns_account varchar(60) not null,
    sns_platform varchar(10) not null, 
    cust_address varchar(200) not null,
    cust_phone varchar(10) not null,
    cust_mail varchar(320) not null,
    primary key(custID)
    );
CREATE TABLE IF NOT EXISTS updated_orders
    (
    orderID varchar(10) not null,
    order_updated_status varchar(254) not null,
    updated_status_date date not null
    );