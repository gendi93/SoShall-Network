CREATE TABLE User (
UserID INTEGER NOT NULL AUTO_INCREMENT,
Email VARCHAR(254) NOT NULL,
Password VARCHAR(255) NOT NULL,
FirstName VARCHAR(255) NOT NULL,
LastName VARCHAR(255) NOT NULL,
DateJoined DATETIME NOT NULL,
DateOfBirth DATE NOT NULL,
Gender TINYINT(1),
CurrentLocation VARCHAR(255),
PhoneNumber VARCHAR(255),
ProfilePhotoID VARBINARY(255),
PRIMARY KEY (UserID)
);

CREATE TABLE circle (
CircleId INTEGER NOT NULL AUTO_INCREMENT,
CircleAdminUserID INTEGER NOT NULL,
DateCreated DATETIME NOT NULL,
CircleTitle VARCHAR(255),
CirclePhotoID INTEGER,
PRIMARY KEY (CircleId)
);

CREATE TABLE circle_member (
CircleMemberID INTEGER NOT NULL AUTO_INCREMENT,
CircleID INTEGER NOT NULL,
MemberUserID INTEGER NOT NULL,
DateJoined DATETIME NOT NULL,
PRIMARY KEY (CircleMemberID)
);

CREATE TABLE privacy_setting (
UserID INTEGER NOT NULL,
SearchVisibility VARCHAR(255) NOT NULL,
FriendVisibility VARCHAR(255) NOT NULL,
PRIMARY KEY (UserID)
);

CREATE TABLE friendship (
FriendshipID INTEGER NOT NULL AUTO_INCREMENT,
User1ID INTEGER NOT NULL,
User2ID INTEGER NOT NULL,
Status INTEGER NOT NULL,
Date DATETIME NOT NULL,
PRIMARY KEY (FriendshipID)
);

CREATE TABLE message (
MessageID INTEGER NOT NULL AUTO_INCREMENT,
SenderUserID VARCHAR(255) NOT NULL,
ReceiverType Integer NOT NULL,
ReceiverUserID VARCHAR(255) NOT NULL,
ReceiverGroupID VARCHAR(255) NOT NULL,
Content VARCHAR(2550) NOT NULL,
TimeSent DATETIME NOT NULL,
PRIMARY KEY (MessageID)
);

CREATE TABLE photo (
PhotoID INTEGER NOT NULL AUTO_INCREMENT,
UserID VARCHAR(255) NOT NULL,
DatePosted DATETIME,
Content VARCHAR(2550) NOT NULL,
AccessRights INTEGER NOT NULL,
FileExtension VARCHAR(255) NOT NULL,
FileSource VARCHAR(255) NOT NULL,
PRIMARY KEY (PhotoID)
);

CREATE TABLE photo_collection (
CollectionID INTEGER NOT NULL AUTO_INCREMENT,
UserID VARCHAR(255) NOT NULL,
DateCreated DATETIME NOT NULL,
CollectionTitle VARCHAR(255) NOT NULL,
PRIMARY KEY (CollectionID)
);

CREATE TABLE photo_comment (
PhotoCommentID INTEGER NOT NULL AUTO_INCREMENT,
PhotoID INTEGER NOT NULL,
CommenterUserID INTEGER NOT NULL,
DatePosted DATETIME NOT NULL,
Content VARCHAR(2550) NOT NULL,
PRIMARY KEY (PhotoCommentID)
);

CREATE TABLE blog (
BlogID INTEGER NOT NULL AUTO_INCREMENT,
UserID VARCHAR(255) NOT NULL,
DatePosted DATETIME,
Content VARCHAR(2550) NOT NULL,
AccessRights INTEGER NOT NULL,
PRIMARY KEY (BlogID)
);

CREATE TABLE blog_comment (
BlogCommentID INTEGER NOT NULL AUTO_INCREMENT,
BlogID INTEGER NOT NULL,
CommenterUserID INTEGER NOT NULL,
DatePosted DATETIME NOT NULL,
Content VARCHAR(2550) NOT NULL,
PRIMARY KEY (BlogCommentID)
);
