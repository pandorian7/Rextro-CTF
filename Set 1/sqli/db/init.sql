CREATE TABLE users (
  id INT AUTO_INCREMENT PRIMARY KEY,
  username VARCHAR(64) NOT NULL,
  password VARCHAR(128) NOT NULL,
  role VARCHAR(32) NOT NULL DEFAULT 'user',
  bio TEXT
);

-- Administrator (Sinhala name)
INSERT INTO users (username, password, role, bio) VALUES ('admin','A9m!lK3y7pQz2rT4vL0sXw1','admin','System administrator.');

-- Regular user
INSERT INTO users (username, password, role, bio) VALUES ('Nadeera','N2d!r9B6pQw4xZ7mL3sV0tP','user','Hello, I am Nadeera.');

-- Project manager
INSERT INTO users (username, password, role, bio) VALUES ('Chathura','C7h@tU2rA9pL6qW3zX0bV5','project manager','Project manager for client work.');

-- User which stores the flag in the bio (target)
INSERT INTO users (username, password, role, bio) VALUES ('Ruwan','R9w!nK8zPq4vT1sL6mD2gC0','user','FLAG{hidden-in-bio-example-12345}');

-- Additional project managers
INSERT INTO users (username, password, role, bio) VALUES ('Kumara','K8m@rA3pQ7zL2vS9tD4wX1','project manager','Project manager.');
INSERT INTO users (username, password, role, bio) VALUES ('Saman','S5m!nK2pQ8vL4tR1zX7dC0','project manager','Project manager.');
INSERT INTO users (username, password, role, bio) VALUES ('Anushka','A4n#sH7kP9vL2tR3zX8dY1','project manager','Project manager.');
