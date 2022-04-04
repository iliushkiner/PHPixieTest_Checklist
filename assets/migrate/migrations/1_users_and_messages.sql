CREATE TABLE users(
  id INT NOT NULL PRIMARY KEY AUTO_INCREMENT,
  name VARCHAR(255) NOT NULL,
  email VARCHAR(255),
  passwordHash VARCHAR(255)
);

-- statement

CREATE TABLE messages(
  id INT PRIMARY KEY AUTO_INCREMENT,
  userId INT NOT NULL,
  text VARCHAR(255) NOT NULL,
  date DATETIME NOT NULL,

  FOREIGN KEY (userId)
      REFERENCES users(id)
);

-- statement

CREATE TABLE checklist(
  id INT PRIMARY KEY AUTO_INCREMENT,
  parentid INT,
  name VARCHAR(255) NOT NULL,
  descr VARCHAR(255),

  FOREIGN KEY (parentid)
      REFERENCES checklist(id)
);

-- statement

CREATE TABLE userchecklist (
  id INT PRIMARY KEY AUTO_INCREMENT,
  checkid INT NOT NULL, 
  userid INT NOT NULL,
  status BOOLEAN NOT NULL DEFAULT FALSE,

  FOREIGN KEY (checkid)
      REFERENCES checklist(id),
  FOREIGN KEY (userId)
      REFERENCES users(id)
);