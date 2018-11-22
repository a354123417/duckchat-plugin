
CREATE TABLE if Not EXISTS _data_site_ (
  id INTEGER PRIMARY KEY AUTO_INCREMENT,
  action VARCHAR(100) not null,
  siteId VARCHAR(100) not null,
  siteAddress VARCHAR(50),
  userId VARCHAR(100) not null,
  addTime BIGINT,
  INDEX(addTime)
);

create table if not EXISTS _data_push_message_ (
  id INTEGER PRIMARY KEY AUTO_INCREMENT,
  siteId VARCHAR(100) not null,
  msgId VARCHAR(100) not null,
  fromUserId VARCHAR(100) not null ,
  roomType INTEGER,
  roomId VARCHAR(100),
  msgType INTEGER,
  content TEXT,
  counter INTEGER,
  sendTime BIGINT,
  INDEX(siteId),
  INDEX(sendTime)
);
