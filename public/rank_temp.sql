-- Android組
-- 查詢參與者週數
SELECT A.u_id, B.exp_id, MAX(A.week), A.start_date
FROM goal A, user B
WHERE A.u_id IN (SELECT id FROM user WHERE exp_id LIKE 'A111____') AND B.id=A.u_id
GROUP BY A.u_id
ORDER BY B.exp_id

-- 查詢參與者前一週日期範圍
SELECT
    A.u_id,
    MAX(A.week)-1 AS `week`,
    DATE_SUB(A.start_date, INTERVAL SUM(C.days)+7 DAY) AS `start_date`,
    DATE_SUB(A.start_date, INTERVAL 1 DAY) AS end_date
FROM
    goal A,
    week_adjust C
WHERE
    A.u_id IN (SELECT id FROM user WHERE exp_id LIKE 'A111____') 
    AND 
    C.u_id=A.u_id 
    AND 
    C.week=A.week-1
GROUP BY A.u_id

-- 查詢前一週有效天數
SELECT
    u_id,
    COUNT(`date`)
FROM 
    (
        SELECT
            A.u_id,
            A.date
        FROM 
            app_usage A,
            -- 查詢參與者前一週日期範圍
            (
                SELECT
                    A.u_id,
                    MAX(A.week)-1 AS `week`,
                    DATE_SUB(A.start_date, INTERVAL SUM(C.days)+7 DAY) AS `start_date`,
                    DATE_SUB(A.start_date, INTERVAL 1 DAY) AS end_date
                FROM
                    goal A,
                    week_adjust C
                WHERE 
                    (A.u_id IN (SELECT id FROM user WHERE exp_id LIKE 'A111____') AND A.start_date IS NOT NULL)
                    AND 
                    C.u_id=A.u_id 
                    AND 
                    C.week=A.week-1
                GROUP BY A.u_id
            ) AS B
        WHERE
            (A.date>=B.start_date AND A.date<=B.end_date)
            AND 
            (B.u_id=A.u_id)
        GROUP BY A.u_id, A.date
    ) AS A
GROUP BY u_id


-- 查詢前一週的平均使用時間
SELECT
    u_id,
    SUM(usage_time)/COUNT(`date`)
FROM 
    (
        SELECT
            A.u_id,
            A.date,
            SUM(A.usage_time) AS usage_time
        FROM 
            app_usage A,
            (
                SELECT
                    A.u_id,
                    MAX(A.week)-1 AS `week`,
                    DATE_SUB(A.start_date, INTERVAL SUM(C.days)+7 DAY) AS `start_date`,
                    DATE_SUB(A.start_date, INTERVAL 1 DAY) AS end_date
                FROM
                    goal A,
                    week_adjust C
                WHERE 
                    (A.u_id IN (SELECT id FROM user WHERE exp_id LIKE 'A111____') AND A.start_date IS NOT NULL)
                    AND 
                    C.u_id=A.u_id 
                    AND 
                    C.week=A.week-1
                GROUP BY A.u_id
            ) AS B
        WHERE
            (A.date>=B.start_date AND A.date<=B.end_date)
            AND 
            (B.u_id=A.u_id)
        GROUP BY A.u_id, A.date
    ) AS A
GROUP BY u_id


-- 查詢前一週平均使用時間少於自己的
SELECT COUNT(*)+1 AS rank
FROM 
    (
        SELECT
            u_id,
            SUM(usage_time)/COUNT(`date`) AS usage_time_mean
        FROM 
            (
                SELECT
                    A.u_id,
                    A.date,
                    SUM(A.usage_time) AS usage_time
                FROM 
                    app_usage A,
                    (
                        SELECT
                            A.u_id,
                            MAX(A.week)-1 AS `week`,
                            DATE_SUB(A.start_date, INTERVAL SUM(C.days)+7 DAY) AS `start_date`,
                            DATE_SUB(A.start_date, INTERVAL 1 DAY) AS end_date
                        FROM
                            goal A,
                            week_adjust C
                        WHERE 
                            (A.u_id IN (SELECT id FROM user WHERE exp_id LIKE 'A111____') AND A.start_date IS NOT NULL)
                            AND 
                            C.u_id=A.u_id 
                            AND 
                            C.week=A.week-1
                        GROUP BY A.u_id
                    ) AS B
                WHERE
                    (A.date>=B.start_date AND A.date<=B.end_date)
                    AND 
                    (B.u_id=A.u_id)
                GROUP BY A.u_id, A.date
            ) AS A
        GROUP BY u_id
    ) AS A
WHERE usage_time_mean<9999


-- iOS組
-- 查詢最新的一週
SELECT
    u_id,
    MAX(`week`)
FROM 
    goal
WHERE
    u_id IN (SELECT id FROM user WHERE exp_id LIKE 'I111____')
GROUP BY
    u_id


-- 查詢參與者前一週日期範圍
SELECT
    u_id,
    DATE_SUB(start_date, INTERVAL 7 DAY) AS `start_date`,
    DATE_SUB(start_date, INTERVAL 1 DAY) AS end_date
FROM
    goal
WHERE
    u_id IN (SELECT id FROM user WHERE exp_id LIKE 'I111____')
GROUP BY u_id


-- 查詢前一週的平均使用時間
SELECT
    u_id,
    SUM(usage_time)/7 AS usage_time_mean
FROM 
    (
        SELECT
            A.u_id,
            SUM(A.usage_time) AS usage_time
        FROM 
            app_usage A,
            -- 查詢參與者前一週日期範圍
            (
                SELECT
                    u_id,
                    DATE_SUB(start_date, INTERVAL 7 DAY) AS `start_date`,
                    DATE_SUB(start_date, INTERVAL 1 DAY) AS end_date
                FROM
                    goal
                WHERE
                    u_id IN (SELECT id FROM user WHERE exp_id LIKE 'I111____')
                GROUP BY u_id
            ) AS B
        WHERE
            (A.date>=B.start_date AND A.date<=B.end_date)
            AND 
            (B.u_id=A.u_id)
        GROUP BY A.u_id
    ) AS A
GROUP BY u_id


-- 查詢前一週平均使用時間少於自己
SELECT
    COUNT(*)
FROM 
    (
        SELECT
            u_id,
            SUM(usage_time)/7 AS usage_time_mean
        FROM 
            (
                SELECT
                    A.u_id,
                    SUM(A.usage_time) AS usage_time
                FROM 
                    app_usage A,
                    -- 查詢參與者前一週日期範圍
                    (
                        SELECT
                            u_id,
                            DATE_SUB(start_date, INTERVAL 7 DAY) AS `start_date`,
                            DATE_SUB(start_date, INTERVAL 1 DAY) AS end_date
                        FROM
                            goal
                        WHERE
                            u_id IN (SELECT id FROM user WHERE exp_id LIKE 'I111____')
                        GROUP BY u_id
                    ) AS B
                WHERE
                    (A.date>=B.start_date AND A.date<=B.end_date)
                    AND 
                    (B.u_id=A.u_id)
                GROUP BY A.u_id
            ) AS A
        GROUP BY u_id
    ) AS A
WHERE usage_time_mean<999