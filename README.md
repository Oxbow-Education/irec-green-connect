# GitHub Repo: irec-green-connect

This repository contains the codebase for the iRec Green Connect project. To set up the development environment for this project, follow the steps below:

## Step 1: Download LocalWP Desktop App

Download and install the LocalWP desktop app from [LocalWP's official website](https://localwp.com/). LocalWP will be used to manage the local development environment.

## Step 2: Create a New Site

- Launch LocalWP and click on "Create Site" to start setting up the project.

## Step 3: Use Custom Settings

- Choose "Custom" settings for the new site and configure the following options:
  - PHP Version: 7.4.3
  - Web Server: Apache
  - Database: MySQL 8

## Step 4: Create User for Database

- During the setup process, create a user with the desired email and password. Note that each local development environment may have different database settings.

## Step 5: Open Site Folder

- Once the site is created, click on "Open Site" to navigate to the project's root folder.

## Step 6: Update wp-config.php

- Inside the site folder, navigate to the "app/public" directory.
- Locate the `wp-config.php` file and move it up one directory to the "app" folder.

## Step 7: Delete Public Folder

- After moving the `wp-config.php` file, you can delete the "public" folder as it is no longer needed.

## Step 8: Open Site Shell

- From the LocalWP dashboard, click on "Open Site Shell" to access the command-line interface for the project.

## Step 9: Change Directory to App Folder

- In the site shell, navigate to the "app" folder using the `cd` command:

```cd app```

## Step 10: Clone the Project Repository

- While inside the "app" folder, run the following command to clone the project repository:

```git clone https://github.com/Oxbow-Education/irec-green-connect.git public```

## Step 11: Open the Site

- Once the repository is cloned, click on "Open Site" in LocalWP to access the website.
- To access the WordPress admin panel, go to `/wp-admin` in the URL and log in using your admin credentials.



## Step 13: Important information about database syncing

- Development on wordpress sites is highly dependent on database content. Anytime you edit the site using the WordPress CMS interface, you are making database changes. In order for us to all have an update version of the latest database, we have to sync this data between each developer and the staging site. 

- To do this, we us a plugin called [WP Synchro](https://wpsynchro.com/). Before you start development, make sure to meet with the code owner (Nina) to set up the synchronization process.

## Step 12: Start Development

- You can now start working on the project by making changes to the codebase located in the `users/<name>/Local Sites/irec-green-connect/app/public` directory.

Feel free to contribute, raise issues, and collaborate with the team to develop the iRec Green Connect project. Happy coding!

