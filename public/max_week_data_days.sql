SELECT COUNT(*)
FROM
(
    SELECT `date`
    FROM app_usage
    WHERE `date` >= (
        SELECT start_date
        FROM goal
        WHERE u_id = 29 AND week = (SELECT MAX(week) FROM goal WHERE u_id = 29)
    ) AND `date` < DATE(NOW())
    GROUP BY `date`
) AS A