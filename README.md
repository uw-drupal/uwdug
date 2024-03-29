# UW Drupal Users Group Community Site

This site has been created by the University of Washington Drupal Users Group to share information about using Drupal and showcase our projects. Developing and maintaining this site is currently a volunteer effort provided by the UW Drupal Community.

**Questions?** Contact the site coordinators at [uwdrupal@uw.edu](mailto:uwdrupal@uw.edu).

## Purpose
This document explains how to get started working with the site's codebase for developers who plan to contribute to the site's maintenance. There will also be opportunities to contribute content on the site itself, which won't require having a copy of the codebase. Please submit suggestions for improving this README in the issue queue.

## Contents
* [Prerequisites](#prereqs)
* [Setting up the site](#setup)
* [Working with the site](#working)
* [Updating Drupal core](#updating-core)
* [Updating contributed modules and themes](#updating-contrib)
* [Updating custom modules and themes](#updating-custom)
* [Applying changes to production site on depts.washington.edu](#applying-production)
* [Authors](#authors)

## <a name="prereqs"></a>Prerequisites
1. A **LAMP development environment** with git and drush installed.

    *For convenience, this project includes Lando configuration files that should work in most OS environments. If you use Lando, you do not need any other LAMP stack or drush on your machine. Use `lando drush` to issue drush commands or log into the php container with `lando ssh`. Setting up [Lando](https://docs.lando.dev/) is beyond the scope of this document.*
2. A **Github account**, with your Github SSH key added to your development environment so you can push and pull from Github as needed.
3. A **Bitbucket account**, with your Bitbucket SSH key added to your development environment so you can pull from submodules hosted on Bitbucket.
4. Read access to the following repositories:
    - https://github.com/uw-drupal/uwdug
    - https://github.com/uw-drupal/uw_boundless
    - https://bitbucket.org/uwartsci/uwtrumba/
5. A copy of the site's **database**. Email [uwdrupal@uw.edu](mailto:uwdrupal@uw.edu) to request this. (If you aren't using Lando, you may also need a copy of **settings.php** and the **files**.)

## <a name="setup"></a>Setting up the site
1. Fork the repository in your account on Github.
2. Clone your fork to your development environment:

    `git clone git@github.com:<username>/uwdug.git`
3. Initialize and update the submodules:
```
cd uwdug
git submodule init
git submodule update
```

If using Lando:

4. Boot Lando with: `lando start`
5. Import the database with `lando db-import <your_sql_file>`
6. Configure the database to run locally: `lando local-conf`. This will enable Stage File Proxy, so you won't need local copies of the files.

For other environments:

4. Import the database. Steps will vary depending on your environment.
5. Put the settings.php and files directory in place, under `sites/default`.
6. Edit the database connection details in settings.php. Change settings.php back to read-only permissions when finished editing.

For all environments:

7. Visit the local site in your browser to confirm that it is running.
8. Disable Google Analytics if the module is installed. We don't want visits to development sites included in our reports.

    `drush dis googleanalytics`

The running site will be accessible at:
<http://uwdug.lndo.site/uwdrupal>

Note that Lando will print URLs for the running environment, but they won't include the path `/uwdrupal` (which matches the server), so they won't work.

## <a name="working"></a>Working with the site

Use drush to generate a login link for the admin user. You can change the password for your local site copy if you want.

    drush uli

You will not be able to use UW NetID login on your local site, because it cannot be registered as a Shibboleth service provider.

If you've made **code changes** that are ready to be incorporated into the main site, submit a pull request on Github. We recommend creating feature branches to make it easier to track your changes and manage pull requests from multiple developers.

If you've made **configuration or content changes** that you'd like to incorporate into the main site, log in and apply the changes there. Any UW employee should have Contributor access. To request Site Builder or Editor access, email uwdrupal@uw.edu.

## <a name="updating-core"></a>Updating Drupal core

We add Drupal as a separate remote repository, so we can fetch changes from Drupal.org and merge them into our project.

One-time setup:

    git remote add drupal https://git.drupal.org/project/drupal.git

Updating core:
```
git fetch drupal
git merge <tagname> --squash
drush updatedb
drush cc all # if there were no database updates
```

Test that the site works as expected. If it does, push the changes.

    git push origin

## <a name="updating-contrib"></a>Updating contributed modules and themes

We use drush to update contributed modules and themes.
```
drush up <modulename>
drush cc all # if there were no database updates
```

Using "drush up" should automatically run any required database updates. Test that the site works as expected. If it does, add and commit the changes.
```
git add --all sites/modules/<modulename>
git commit -m "<message explaining the commit>"
git push origin
```

## <a name="updating-custom"></a>Updating custom modules and themes

We use [git submodules](https://git-scm.com/book/en/v2/Git-Tools-Submodules) to include separate modules and themes maintained by the UW Drupal community. This way they maintain their own git project history and developers can make changes to the submodules and choose which versions to include on the site.

**To-do:** add more details about git submodules.

## <a name="applying-production"></a>Applying changes to production site on depts.washington.edu

A few community members have permission to update the code on the production site. If you have access to `actas uwdrupal`, please do not edit code there directly. Pull all the changes from the master branch of the repository. Use the following command in the `public_html` folder:

    git pull

/Nota bene:/ That command previously included `--rebase` in order to preserve a `RewriteBase` directive in `.htaccess` on Ovid whcih broke local development. That difference is now handled by using `.htaccess-lando`, which Lando prefers to `.htaccess`.

If submodules have been updated, run the following command to checkout the correct subproject commits:

    git submodule update

Then use drush to run database updates (if needed) and clear caches.

## <a name="authors"></a>Authors

- [UW Drupal Users Group](https://depts.washington.edu/uwdrupal/)
