CREATE TABLE ec_site_user (
    user_id INT NOT NULL AUTO_INCREMENT,
    user_name VARCHAR(32) NOT NULL,
    password VARCHAR(32) NOT NULL,
    PRIMARY KEY (user_id)
);

CREATE TABLE ec_site_product (
    product_id INT NOT NULL AUTO_INCREMENT,
    product_name VARCHAR(64) NOT NULL,
    price INT NOT NULL,
    stock_qty INT NOT NULL,
    product_image VARCHAR(256) NOT NULL,
    public_flg INT NOT NULL,
    PRIMARY KEY (product_id)
);

CREATE TABLE ec_site_cart (
    user_id INT NOT NULL,
    product_id INT NOT NULL,
    product_qty INT NOT NULL,
    FOREIGN KEY (user_id) REFERENCES ec_site_user(user_id),
    FOREIGN KEY (product_id) REFERENCES ec_site_product(product_id)
);

CREATE TABLE ec_site_unique_filename (
    id INT NOT NULL
);

INSERT INTO ec_site_unique_filename (id) VALUES (0);
