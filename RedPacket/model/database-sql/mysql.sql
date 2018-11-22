
CREATE TABLE IF NOT EXISTS RedPacketAccount (
  id INTEGER PRIMARY KEY AUTO_INCREMENT,
  userId VARCHAR(100) unique not null,
  amount decimal(4,2))DEFAULT CHARSET=utf8mb4 COLLATE utf8mb4_general_ci;

CREATE TABLE IF NOT EXISTS RedPacketRecords (
  id INTEGER PRIMARY KEY AUTO_INCREMENT,
  packetId VARCHAR(50) unique not null,
  userId VARCHAR(100) not null,
  totalAmount decimal(4,2),
  quantity int,
  description VARCHAR(100),
  isGroup boolean,
  roomId VARCHAR(100) not null,
  sendTime BIGINT)DEFAULT CHARSET=utf8mb4 COLLATE utf8mb4_general_ci;

CREATE TABLE IF NOT EXISTS RedPacketGrabbers (
  id INTEGER PRIMARY KEY AUTO_INCREMENT,
  packetId VARCHAR(50) not null,
  userId VARCHAR(100) not null,
  amount decimal(4,2),
  grabTime BIGINT,
  UNIQUE (packetId,userId))DEFAULT CHARSET=utf8mb4 COLLATE utf8mb4_general_ci;;
