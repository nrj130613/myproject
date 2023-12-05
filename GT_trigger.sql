use goodstracking;
DELIMITER $
CREATE TRIGGER new_updated_status BEFORE UPDATE on orders
	FOR EACH ROW
	BEGIN
        INSERT INTO updated_orders
        VALUES (OLD.orderID, OLD.order_latest_status, OLD.latest_status_date);
	END
DELIMITER ;