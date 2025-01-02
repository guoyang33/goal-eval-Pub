-- 查詢上一週的日期範圍
-- 第0週
-- 使用user.start_date跟DATE_SUB(DATE(NOW()), INTERVAL 1 DAY)當日期範圍


-- 查詢計畫開始日到今天以前的日平均
SELECT
    SUM(usage_time)/COUNT(`date`) AS usage_time_mean
FROM 
    (
        SELECT
            `date`,
            SUM(usage_time) AS usage_time
        FROM
            app_usage
        WHERE 
            u_id=29
            AND
            date>=(SELECT start_date FROM user WHERE id=29)
            AND
            date<DATE(NOW())
        GROUP BY
            `date`
    ) AS A

-- 依類別分類
SELECT
    app_category,
    SUM(usage_time)/COUNT(`date`) AS usage_time_mean
FROM 
    (
        SELECT
            `date`,
            app_category,
            SUM(usage_time) AS usage_time
        FROM
            app_usage
        WHERE 
            u_id=29
            AND
            date>=(SELECT start_date FROM user WHERE id=29)
            AND
            date<DATE(NOW())
        GROUP BY
            `date`, app_category
    ) AS A
GROUP BY
    app_category


-- 查詢上一週日平均
SELECT
    app_category,
    SUM(usage_time)/COUNT(`date`) AS usage_time_mean
FROM 
    (
        SELECT
            `date`,
            app_category,
            SUM(usage_time) AS usage_time
        FROM
            app_usage
        WHERE 
            u_id=:u_id
            AND
            date>=(SELECT `start_date` FROM goal WHERE u_id=29 AND week=1)
            AND
            date<DATE(NOW())
        GROUP BY
            `date`, app_category
    ) AS A
GROUP BY
    app_category