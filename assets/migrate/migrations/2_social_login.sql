ALTER TABLE users ADD COLUMN twitterId VARCHAR(255) AFTER passwordHash;

-- statement

ALTER TABLE users ADD COLUMN facebookId VARCHAR(255) AFTER twitterId;

-- statement

ALTER TABLE users ADD COLUMN googleId VARCHAR(255) AFTER facebookId;

-- statement

ALTER TABLE users ADD COLUMN vkId VARCHAR(255) AFTER googleId;