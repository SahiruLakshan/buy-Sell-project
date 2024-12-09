CREATE TABLE IF NOT EXISTS `users` (
  `uid` int(11) NOT NULL AUTO_INCREMENT,
  `user` varchar(255) DEFAULT NULL,
  `gender` varchar(10) DEFAULT NULL,
  `dob` varchar(15) DEFAULT NULL,
  `phone` int(10) DEFAULT NULL,
  `pass` varchar(100) DEFAULT NULL,
  `email` varchar(255) DEFAULT NULL,
  PRIMARY KEY (`uid`),
  UNIQUE KEY `username` (`user`),
  UNIQUE KEY `email` (`email`)
) ENGINE=InnoDB  DEFAULT CHARSET=latin1 AUTO_INCREMENT=5 ;
INSERT INTO `users` (`uid`, `user`, `gender`, `dob`, `phone`, `pass`, `email`) 
VALUES('0', 'uoc', '', '', '0', '', 'uoc@gmail.com'),
('1', 'john', 'male', '1978-12-03', '012548769', 'john1', 'john@gmail.com'), 
('2', 'kate', 'female', '1956-06-08', '0778965324', 'kate2', 'kate@gmail.com'),
('3', 'mary', 'female', '2000-05-08', '0745865324', 'mary3', 'mary@gmail.com'),
('4', 'ken', 'female', '1999-06-08', '077835324', 'ken4', 'ken@gmail.com');

INSERT INTO `users` (`uid`, `user`, `gender`, `dob`, `phone`, `pass`, `email`) 
VALUES('6', 'admin', '', '', '0', '', 'admin@gmail.com');

CREATE TABLE IF NOT EXISTS `product_category` (
  `prod_type` VARCHAR(10) NOT NULL,
  `prod_care` VARCHAR(255) DEFAULT NULL,
  PRIMARY KEY (`prod_type`)
) ENGINE=InnoDB DEFAULT CHARSET=latin1;

INSERT INTO `product_category` (`prod_type`, `prod_care`)
VALUES ('A', 'face care'),
('B', 'body care'),
('C', 'fragrances');

CREATE TABLE IF NOT EXISTS `products` (
  `pid` INT(3) ZEROFILL AUTO_INCREMENT,
  `prod_name` VARCHAR(255) DEFAULT NULL,
  `price` VARCHAR(15) DEFAULT NULL,
  `prod_type` VARCHAR(10) DEFAULT NULL,
  PRIMARY KEY (`pid`),
  FOREIGN KEY (prod_type) REFERENCES product_category(prod_type)
) ENGINE=InnoDB DEFAULT CHARSET=latin1;

INSERT INTO `products` (`pid`, `prod_name`, `prod_type`, `price`)
VALUES
('001', 'Jasmin-facial skin toner 100 ml', 'A', '1500/='),
('002', 'white mint- facial cleanser 150 ml', 'A', '1700/='),
('003', 'turmeric-renewing serum 60 ml', 'A', '2800/='),
('004', 'sal-safron face cream 100 ml', 'A', '2200/='),
('005', 'anti-pollution day face essence 30 ml', 'A', '1460/='),
('006', 'sandalwood- face oil 60 ml', 'A', '1250/='),
('007', 'pink lotus- body lotion 250 ml', 'B', '2400/='),
('008', 'aloe vera soothing gel 200 ml', 'B', '2150/='),
('009', 'sandalwood bath & shower gel 250 ml', 'B', '2750/='),
('010', 'sandalwood body scrub 250 ml', 'B', '2300/='),
('011', 'jasmine body cleanser 400 ml', 'B', '4400/='),
('012', 'royal lotus- body spray 200 ml', 'C', '2750/='),
('013', 'jasmin body- spray 200 ml', 'C', '2750/='),
('014', 'blu water lily- body spray 200 ml', 'C', '2750/=');





