CREATE TABLE IF NOT EXISTS RedPacketAccount (
  id INTEGER PRIMARY KEY AUTOINCREMENT,
  userId VARCHAR(100) unique not null,
  amount decimal(4,2));

CREATE TABLE IF NOT EXISTS RedPacketRecords (
  id INTEGER PRIMARY KEY AUTOINCREMENT,
  packetId VARCHAR(50) unique not null,
  userId VARCHAR(100) not null,
  totalAmount decimal(4,2),
  quantity int,
  description VARCHAR(100),
  isGroup boolean,
  roomId VARCHAR(100) not null,
  sendTime BIGINT);

CREATE INDEX IF NOT EXISTS indexRedPacketRecordsRoomId ON RedPacketRecords(roomId);

CREATE TABLE IF NOT EXISTS RedPacketGrabbers (
  id INTEGER PRIMARY KEY AUTOINCREMENT,
  packetId VARCHAR(50) not null,
  userId VARCHAR(100) not null,
  amount decimal(4,2),
  grabTime BIGINT,
  UNIQUE (packetId,userId));