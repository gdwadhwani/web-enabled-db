
1) Question 1
Select concat(b.d_first_name, ' ', b.d_last_name) as "Director", concat(a.a_first_name, ' ', a.a_last_name) as "Actors/Actress", count(*) as "No of Movies", min(c.year) as "From Year", max(c.year) as "To Year"
FROM actors_actresses as a, directors as b, movies as c, directorship as d, roles as e
WHERE e.movie_id = d.movie_id And e.act_id = a.act_id AND d.director_id = b.director_id AND e.movie_id = c.movie_id AND d.movie_id = c.movie_id
Group By e.act_id, d.director_id
ORDER By count(*) desc, b.d_last_name, a.a_last_name;

2) Question 2
Select concat(b.d_first_name, ' ', b.d_last_name) as "Director", concat(a.a_first_name, ' ', a.a_last_name) as "Actors/Actress", count(*) as "No of Movies", min(c.year) as "From Year", max(c.year) as "To Year"
FROM actors_actresses as a, directors as b, movies as c, directorship as d, roles as e
WHERE e.movie_id = d.movie_id And e.act_id = a.act_id AND d.director_id = b.director_id AND e.movie_id = c.movie_id AND d.movie_id = c.movie_id AND a.act_id = 1 And b.director_id = 1
Group By e.act_id, d.director_id
ORDER By count(*) desc, b.d_last_name, a.a_last_name;

3) Question 3
SELECT concat(b.d_first_name, ' ', b.d_last_name) as "Director", concat(a.a_first_name, ' ', a.a_last_name) as "Actors/Actress", c.title, c.year, e.role_name
FROM actors_actresses as a, directors as b, movies as c, directorship as d, roles as e
WHERE c.movie_id = e.movie_id AND c.movie_id = d.movie_id AND e.act_id = a.act_id AND d.director_id = b.director_id
ORDER By c.year, b.d_last_name, a.a_last_name; 

4) Question 4
SELECT concat(b.d_first_name, ' ', b.d_last_name) as "Director", concat(a.a_first_name, ' ', a.a_last_name) as "Actors/Actress", c.title, c.year, e.role_name
FROM actors_actresses as a, directors as b, movies as c, directorship as d, roles as e
WHERE c.movie_id = e.movie_id AND c.movie_id = d.movie_id AND e.act_id = a.act_id AND d.director_id = b.director_id AND a.act_id = 1 AND b.director_id = 1
ORDER By c.year, b.d_last_name, a.a_last_name;