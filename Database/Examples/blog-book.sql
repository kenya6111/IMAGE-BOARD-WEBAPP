-- drop table UserSetting;
-- drop table Subscription;
-- drop table PostLike;
-- drop table PostTaxonomy;
-- drop table TaxonomyTerm;
-- drop table Comment;
-- drop table Post;
-- drop table User;
-- drop table Taxonomy;
-- drop table CommentLike;


CREATE TABLE  IF NOT EXISTS  User
(user_id INT NOT NULL,
 username VARCHAR(50) ,
 email VARCHAR(50) ,
 password VARCHAR(50) ,
 created_at DATE ,
 updated_at DATE ,
 PRIMARY KEY (user_id));

 CREATE TABLE  IF NOT EXISTS  Subscription
(subscription_id INT NOT NULL,
 subscription VARCHAR(100),
 subscription_status VARCHAR(100),
 subscriptionCreatedAt VARCHAR(100),
 subscriptionEndsAt VARCHAR(100),
 user_id INT,
 PRIMARY KEY (subscription_id),
 FOREIGN KEY(user_id) REFERENCES User(user_id));

 CREATE TABLE  IF NOT EXISTS  UserSetting
(entry_id INT NOT NULL,
 user_id INT ,
 metaKey VARCHAR(50),
 metaValue VARCHAR(50),
 PRIMARY KEY (entry_id),
 FOREIGN KEY(user_id) REFERENCES User(user_id));

 CREATE TABLE  IF NOT EXISTS  Post
(post_id INT NOT NULL,
 title VARCHAR(50) ,
 content TEXT ,
 created_at DATE ,
 updated_at DATE ,
 user_id INT ,
 PRIMARY KEY (post_id),
 FOREIGN KEY(user_id) REFERENCES User(user_id));

  CREATE TABLE  IF NOT EXISTS  Taxonomy
(taxonomy_id INT NOT NULL,
 taxonomy_name VARCHAR(50) ,
 description VARCHAR(50) ,
 PRIMARY KEY (taxonomy_id));

  CREATE TABLE  IF NOT EXISTS  TaxonomyTerm
(taxonomyTerm_id INT NOT NULL,
 taxonomyTerm_name VARCHAR(50) ,
 taxonomy_id INT ,
 description VARCHAR(50) ,
 parentTaxnomyTerm INT ,
 user_id INT ,
 PRIMARY KEY (taxonomyTerm_id),
 FOREIGN KEY(taxonomy_id) REFERENCES Taxonomy(taxonomy_id));

--  CREATE TABLE  IF NOT EXISTS  Category
-- (category_id INT NOT NULL,
--  category_name VARCHAR(50) ,
--  PRIMARY KEY (category_id));

--  CREATE TABLE  IF NOT EXISTS  Tag
-- (tag_id INT NOT NULL,
--  tag_name VARCHAR(50) ,
--  PRIMARY KEY (tag_id));

 CREATE TABLE  IF NOT EXISTS  PostTaxonomy
(postTaxonomy_id INT NOT NULL,
 post_id INT ,
 taxonomy_id INT ,
 PRIMARY KEY (postTaxonomy_id),
 FOREIGN KEY(post_id) REFERENCES Post(post_id),
 FOREIGN KEY(taxonomy_id) REFERENCES Taxonomy(taxonomy_id));

CREATE TABLE  IF NOT EXISTS  PostLike
(user_id INT NOT NULL,
 post_id INT NOT NULL, 
 PRIMARY KEY (user_id, post_id),
 FOREIGN KEY(user_id) REFERENCES User(user_id),
 FOREIGN KEY(post_id) REFERENCES Post(post_id));

 CREATE TABLE  IF NOT EXISTS  Comment
(comment_id INT NOT NULL,
 comment_text VARCHAR(50),
 created_at DATE ,
 updated_at DATE,
 user_id INT ,
 post_id INT ,
 PRIMARY KEY (comment_id),
 FOREIGN KEY(user_id) REFERENCES User(user_id),
 FOREIGN KEY(post_id) REFERENCES Post(post_id));

CREATE TABLE  IF NOT EXISTS  CommentLike
(user_id INT NOT NULL,
 comment_id INT NOT NULL, 
 PRIMARY KEY (user_id, comment_id),
 FOREIGN KEY(user_id) REFERENCES User(user_id),
 FOREIGN KEY(comment_id) REFERENCES Comment(comment_id));




 