--- de phpmyadmin

CREATE TABLE gamification (
    gam_id INT NOT NULL AUTO_INCREMENT ,
    gam_created_pages INT NOT NULL default 0,
    gam_modified INT NOT NULL default 0, 
    gam_user_id int unsigned NOT NULL default 0,
    gam_user_text varchar(255) binary NOT NULL,
    gam_logins INT unsigned NOT NULL default 0,
    PRIMARY KEY (`gam_id`)
)