
CREATE TABLE IF NOT EXISTS DuckChatUserAccount (
  id INTEGER PRIMARY KEY AUTO_INCREMENT,
  userId VARCHAR(100) unique not null,
  amount decimal(11,2),
  status int,
  createTime BIGINT)DEFAULT CHARSET=utf8mb4 COLLATE utf8mb4_general_ci;

CREATE TABLE IF NOT EXISTS DuckChatUserAccountRecords (
  id INTEGER PRIMARY KEY AUTO_INCREMENT,
  userId VARCHAR(100) not null,
  amount decimal(11,2) not null,
  type int not null,
  remarks text,
  status int,
  createTime BIGINT,
  INDEX(userId))DEFAULT CHARSET=utf8mb4 COLLATE utf8mb4_general_ci;

CREATE TABLE IF NOT EXISTS DuckChatRedPacket (
  id INTEGER PRIMARY KEY AUTO_INCREMENT,
  packetId VARCHAR(50) unique not null,
  userId VARCHAR(100) not null,
  totalAmount decimal(6,2),
  quantity int,
  description VARCHAR(100),
  isGroup boolean,
  roomId VARCHAR(100) not null,
  sendTime BIGINT,
  finishTime BIGINT)DEFAULT CHARSET=utf8mb4 COLLATE utf8mb4_general_ci;

CREATE TABLE IF NOT EXISTS DuckChatRedPacketGrabbers (
  id INTEGER PRIMARY KEY AUTO_INCREMENT,
  packetId VARCHAR(50) not null,
  userId VARCHAR(100),
  amount decimal(6,2),
  number int,
  status int,
  grabTime BIGINT,
  UNIQUE (packetId,userId))DEFAULT CHARSET=utf8mb4 COLLATE utf8mb4_general_ci;
