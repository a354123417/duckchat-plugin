
CREATE TABLE if Not EXISTS  platformUser (
  id INTEGER PRIMARY KEY AUTO_INCREMENT,
  userId VARCHAR(100) not null,
  phoneNumber VARCHAR(11) not null ,/* comment "用户手机号"*/
  phoneCountryCode VARCHAR(10) not null default '86',
  loginName VARCHAR(100)  not null,
  nickname VARCHAR(100)  not null,
  avatar TEXT,
  timeReg BIGINT ,/*comment "创建时间"*/
  UNIQUE (phoneNumber),
  UNIQUE (userId),
  UNIQUE (loginName)
);
