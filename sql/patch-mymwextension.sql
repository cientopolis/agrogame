--- de phpmyadmin

CREATE TABLE gamification (
    gam_id INT NOT NULL AUTO_INCREMENT ,
    gam_first_page_created BOOLEAN NOT NULL default FALSE,
    gam_first_page_modified BOOLEAN NOT NULL default FALSE, 
    gam_user_id int unsigned NOT NULL default 0,
    gam_user_text varchar(255) binary NOT NULL,
    gam_logins INT unsigned NOT NULL default 0,
    gam_number_of_colaboration INT NOT NULL DEFAULT 0,
    PRIMARY KEY (`gam_id`)
)