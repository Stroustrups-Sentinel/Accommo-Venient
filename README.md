## Accommo-Venient 🏠

###### * this is an old `antiqued` project along with its imperfections, it serves to showcase my coding journey as a milestone to look back at. 

    - an old group project from 2020,during my 2nd year 
      of college.
    - i think by then i had not joined github, so my way 
      of version management was zipping files and giving 
      the zipfile names a time and date so i can know 
      when i last did changes haha😂.
    - well to think it was "in an optimistic way" the same 
      way github handles repos, except its much more 
      efficient and does it in a distributed way.
---

### File structure
    -/📂css           (css files)
    -/📂design        (schema ERD and wireframe)
    -/📂javascript    (vanilla js files)
    -/📂jquery        (jquery js files)
    -/📂media         (multimedia files)
    -/📂n_php         
    -/📂php           (php files | main codebase)
    -/📂pictureDB     (images stored from user uploads)
    -/📂resources     (website resources)
    -/📄accommo_venientdb.sql    (SQL script | used MysqlDB)
    -/📄*.html        (page html file)
    -/📄Dockerfile    (dockerfile)
    -/📄mysql.dockerfile    (mysql dockerfile) 
    -/📄README.md     (readme doc) 

---

### Setting up project

1. Start an instance of MysqlDB or MariaDB server
2. Add the **Accommo-Venient** database by either doing so: 
   1. Import and run the sql script `/📄accommo_venientdb.sql`
   2. Copy and run the database creation script in `/📄accommo_venientdb.sql` on `line 22` to `line 34`
      1. Copy the remaining script-code and execute section by <br>section with the help of comments to help with the <br>sections and purpose of the script.
3. Make sure to match your db connection credentials with the coded credentials or change them in the following files inside the `/📂php` folder.

|Filename|Line number|
|--------|-----------|
|activation.php|15|
|filemanip.php|261|
|forgot-password.php|12|
|house-details.php|11|
|landlord-user.php|147|
|login.php|27|
|regular-user.php|136|
|search.php|30|
|trial.php|13|

---

### Running the project

   - Now that the setup is complete, you can now run the project
   - You can use [**docker**](https://www.docker.com/products/docker-desktop/) build and run the dockerfile  `./📄Dockerfile` or if you don't want to install the softwares manually 
     -  👉 Make sure you have **php** installed or get it here [<https://php.net>] or [<https://www.apachefriends.org/>] .
   - Inside the project's root folder `/`  open your terminal and start the php server on a port of your choosing e.g on port 8080 we run the command 
   ```bash
   php -S localhost:8080
   ```


---
<details>

  <summary> <h3>Schema ERD</h3> </summary>

  ![erd-image](https://raw.githubusercontent.com/Stroustrups-Sentinel/Accommo-Venient/64cc0659291823a1a83136259aa3f5532c64d257/pictureDB/bd2c726d7ff0ebe4379d04fe0ca5a82d.svg)


</details>

---

😎 **FREE!** ebooks: [<https://goalkicker.com>]
###### *they are very good for use as <u>reference books</u> than reading from scratch, unless you're quite seasoned in the fine arts of programming.
###### *personally i do use them when coding since you cant memorize everything in a programming language unless its the only thing you code in and it doesn't get updated.

### Story continuation

    - arguably one of my most painful project (growth 
      pains) and best project in terms of experience, wow 
      and appreciation of development. This is where the 
      love for php, web development, design, css and 
      dynamic pages (using ajax) came from. 
    - we couldn't complete when it was time for 
      submission due to scope creep and the 
      sheer excitement of what we could do and how 
      awesome it was, all the things we could do, all 
      the functions we could add to make it cool.
    - For me personally it was like discovering a new 
      toy that could do all kinds of amazing stuff you 
      had not given thought to.

🆓 OSS GUI prototyping: [<https://pencil.evolus.vn/>]
###### *although these days im more into [`lunacy`](https://icons8.com/lunacy) and [`figma`](https://figma.com), those days [`pencil`](https://pencil.evolus.vn) was my first intro to wireframing, the experience was a nice and simple one.

    - fast forward to 2023 and out of nostalgia this 
      project is still on my mind and ive decided to 
      upload it and add it to the list of projects on my 
      portfolio, showcasing where i came from and also a 
      good feel and boost of self confidence. i am proud 
      of this group project, i guess i really did come a 
      long way hehe.
    - will now deploy it and try not to change the 
      source code to keep it in its 'antiqued' state 
      unless necessary.

*[**Link to the portfolio 🎁**](https://stroustrups-sentinel.github.io/readme/)