CREATE TABLE IF NOT EXISTS MEMITY_ACCOUNT(
    ID INT(11) PRIMARY KEY AUTO_INCREMENT NOT NULL,
    Userid varchar(200) NOT NULL,
    Email varchar(100) NOT NULL,
    Username varchar(50) NOT NULL,
    Password varchar(150) NOT NULL,
    Country varchar(10) NOT NULL,
    AVATAR varchar(255) NOT NULL,
    DATE timestamp
);

INSERT INTO MEMITY_ACCOUNT (Userid, Email, Username, Password, Country, Avatar) 
VALUES 
("$2y$10$BgPeGdz6RblUq1DAiYM/QuaufblCpZ8WWT8mCtk0sfKEwR9O5E59q", 'admin@admin.com','admin', '$2y$10$cM43CrEEQBpwGcTzyvgh4ui.1Ogi89Oi2N/5NXIZ16SwnWrx6n.VC',
'PL','https://avatarfiles.alphacoders.com/171/thumb-171412.png')
CREATE TABLE IF NOT EXISTS MEMITY_POST(
    ID INT(11) PRIMARY KEY AUTO_INCREMENT NOT NULL,
    Userid varchar(200) NOT NULL,
    Postuser varchar(50),
    Postid varchar(200) NOT NULL,
    Posturl varchar(250) NOT NULL,
    Posttext Text,
    Postview INT(11),
    Posttype varchar(10) NOT NULL,
    Postdate timestamp
);

CREATE TABLE IF NOT EXISTS MEMITY_VOTE(
    ID INT(11) PRIMARY KEY AUTO_INCREMENT NOT NULL,
    Postid varchar(200) NOT NULL,
    Username varchar(50) NOT NULL,
    Type varchar(50) NOT NULL,
    Date timestamp
);
CREATE TABLE IF NOT EXISTS MEMITY_RELATIONS(
    ID INT(11) PRIMARY KEY AUTO_INCREMENT NOT NUll,
    User_id_a varchar(200) NOT NULL,
    User_id_b varchar(200),
    Type varchar(30) NOT NULL,
    Date timestamp
);
CREATE TABLE IF NOT EXISTS MEMITY_NOTIFICATION(
    ID INT(11) PRIMARY KEY AUTO_INCREMENT NOT NULL,
    Postid varchar(200),
    User_id_a varchar(200) NOT NULL,
    User_id_b varchar(200) NOT NULL,
    Data varchar(250),
    Type varchar(30) NOT NULL,
    Date timestamp
);
CREATE TABLE IF NOT EXISTS MEMITY_COMMENT(
    ID INT(11) PRIMARY KEY AUTO_INCREMENT NOT NULL,
    Postid varchar(200) NOT NULL,
    Userid varchar(200) NOT NULL,
    Comment_user varchar(50) NOT NULL,
    Comment_text Text NOT NULL,
    Comment_ID varchar(200) NOT NULL,
    Comment_type varchar(20),
    Comment_date varchar(20) NOT NULL
);
CREATE TABLE IF NOT EXISTS MEMITY_STATUS(
    ID INT(11) PRIMARY KEY AUTO_INCREMENT NOT NULL,
    Userid varchar(200) NOT NULL,
    Status varchar(15) NOT NULL,
    Date  varchar(30) NOT NULL
);