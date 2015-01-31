-- patch_history --

DROP TABLE IF EXISTS patch_history;

CREATE TABLE patch_history (
  patch_history_Id INT unsigned NOT NULL auto_increment, PRIMARY KEY (patch_history_Id),
  patch_history_Number INT NOT NULL default 0,
  patch_history_Action VARCHAR(10) NOT NULL default 'patch',
  patch_history_CreateDate timestamp DEFAULT CURRENT_TIMESTAMP
) ENGINE=InnoDB COLLATE utf8mb4_unicode_ci;

INSERT INTO patch_history SET patch_history_Number = 1;

-- Log --

DROP TABLE IF EXISTS log;

CREATE TABLE log (
  Log_Id INT unsigned NOT NULL auto_increment, PRIMARY KEY (Log_Id),
  Log_Type CHAR(10) NOT NULL default '',
  Log_Message TEXT NOT NULL default '',
  Log_CreateDate timestamp DEFAULT CURRENT_TIMESTAMP
) ENGINE=InnoDB COLLATE utf8mb4_unicode_ci;


-- User --

DROP TABLE IF EXISTS user;

CREATE TABLE user (
  User_Id INT unsigned NOT NULL auto_increment, PRIMARY KEY (User_Id),
  User_Username CHAR(32) NOT NULL DEFAULT '',
  User_Password CHAR(40) NOT NULL DEFAULT '',
  User_DisplayName VARCHAR(128) NOT NULL DEFAULT '',
  User_Level TINYINT NOT NULL DEFAULT 0
) ENGINE=InnoDB COLLATE utf8mb4_unicode_ci;

INSERT INTO user SET User_Username = 'peter', User_Password = SHA('peter'), User_DisplayName = 'Ken', User_Level = 2;
INSERT INTO user SET User_Username = 'admin', User_Password = SHA('admin'), User_DisplayName = 'Admin', User_Level = 1;


-- Log2 --

DROP TABLE IF EXISTS log2;

CREATE TABLE log2 (
  Log2_Id INT unsigned NOT NULL auto_increment, PRIMARY KEY (Log2_Id),
  Log2_Type CHAR(10) NOT NULL default '',
  Log2_Message TEXT NOT NULL default '',
  Log2_CreateDate timestamp DEFAULT CURRENT_TIMESTAMP
) ENGINE=InnoDB COLLATE utf8mb4_unicode_ci;