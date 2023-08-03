Administrator Tools
====================

This section describes how to use the Admin tools for WPHP. 

Admin Panel
===========

Most admin tasks can be accessed via the admin menu bar, which allows admin users to manage :ref:`content types <admin-content-types>`, :ref:`comments <admin-comments>`, :ref:`blog posts <admin-blog-posts>`, and :ref:`users <admin-users>`.

The following applies to administrators with :ref:`ROLE_ADMIN <admin-users-roles-admin>` level privileges unless otherwise specified. See :ref:`Users types/roles <admin-users-roles>` for more information about user privileges.

.. _admin-content-types:

Content Types
-------------

Access to the content types are available from the admin menu. No editing features or methods are available through the primary WPHP interface at this time.

.. _admin-comments:

Comments
--------

For each entry in WPHP (:ref:`title <title-list>`, :ref:`person <person-list>`, or :ref:`firm <firm-list>`), users can submit suggestions for improvement via the comment form at the bottom of entry. Users must supply their full name, email address, and the content of their comment. Users can optionally check that they would like for the WPHP admin to follow up on their comment. No user account is needed to submit comments.

Comments can be managed by users with :ref:`ROLE_ADMIN <admin-users-roles-admin>` and :ref:`ROLE_COMMENT_ADMIN <admin-users-roles-comment>` level privileges.

Comment List
^^^^^^^^^^^^

The comment list page shows a list of all comments for all WPHP entries. Each comment included in the list includes its :ref:`status <admin-comment-status>`, the content of the comment, the submitter's full name, and the entity for which the comment was made, including the content type (:ref:`title <title-list>`, :ref:`person <person-list>`, or :ref:`firm <firm-list>`) and the name of the entry.

Comments can be filtered by status on the comment list page.

Comments can be searched using the **search** button on the righthand side above the comment list.

Each comment can be selected by clicking the hyperlinked text of the individual comment. The comment page includes the commenter's full name, email, :ref:`status <admin-comment-status>`, whether the commenter has requested a follow up, the entity the comment belongs to, the content of the comment, the complete time stamp (YYYY-MM-DD HH:\MM:SS) for when the comment was created, and th time stamp for when the comment was last updated.

The status may be updated from this screen. The full name, email, follow up checkbox, content, and :ref:`status <admin-comment-status>` may be updated.

A :ref:`note <admin-comment-notes>` may also be appended to the comment from this screen.

The comment may also be deleted from this screen via the **delete** button at the top of the screen.

.. _admin-comment-notes:

Comment Notes
^^^^^^^^^^^^^

All notes attached to comments are listed on the Comment Notes page. The page lists comment notes in reverse chronological order and for each entry include which user created the note, the content of the note, and complete time stamp (YYYY-MM-DD HH:\MM:SS) for when the note was created.

Notes can be searched via the **search** button at the top of the Comment Note List.

Note content is hyperlinked to the original comment where further revisons can be made. 

.. _admin-comment-status:

Comment Status
^^^^^^^^^^^^^^

The comment status lists the existing comment status labels. New status labels can can be created via the **new** button at the top of the screen.

Comment statuses currently include the following:

Submitted
  The comment has been submitted and needs review by a site moderator or administrator.
  
Spam
  The comment has been reviewed and is junk. No further action is necessary.
  
Completed
  The comment has been reviewed and appropriate action has been taken. The comment will not be published.

Published
  The comment has been reviewed and appropriate action has been taken. The comment will be published in public.

.. _admin-feedback:

Feedback
--------

Users can supply feedback for the WPHP website as a whole via the form accessible from the **Feedback** link in the top menu. Users must supply their full name, email address, and the content of their feedback. No user account is needed to submit comments.

Users with :ref:`ROLE-ADMIN <admin-users-roles-admin>` priviliges may view any feedback via the same **Feedback** button while logged in. The Feedback List shows all feedback, including the date the feedback was provided, the commenter's name, email, and the full content of their feedback. 

.. _admin-blog-posts:

Blog Posts
----------

A list of recent blog posts is accessible from the menu under :menuselection:`Announcements --> All Announcements` and from :menuselection:`Admin --> Blog Posts`.

Blog posts
^^^^^^^^^^

The Blog Post page lists all recent blog posts, including the title, :ref:`status <admin-blog-statuses>`, an excerpt from the post, the complete time stamp (YYYY-MM-DD HH:MM/:SS) for time posted, the user who authored the post and the blog :ref:`category <admin-blog-categories>`. 

Blog posts can be created via the new tab at the top of the Blog Posts page. On the Post Creation page, a post must be given a title, excerpt, content, :ref:`category <admin-blog-categories>`, and :ref:`status <admin-blog-statuses>`. A full rich text editor is available for excerpt and content input. Users can choose to provide an excerpt for the post or the excerpt field can be left blank and one will be generated automatically.

.. how many recent blog posts are displayed?

.. _admin-blog-statuses:

Post Statuses
^^^^^^^^^^^^^

The Post Statuses page lists all current post status categories in reverse chronological order. Each status is identified by its name, label, description, whether the status makes the post public, the complete time stamp (YYYY-MM-DD HH:MM/:SS) for when it was created, and the complete timestamp for when the category was last updated.

New post statuses can be created via the **new** button at the top of the Post Status List. A name, label, and description must be provided. The Public checkbox may be ticked to make posts with this status publicly visible. 

.. _admin-blog-categories:

Post Categories
^^^^^^^^^^^^^^^

The Post Categories page lists all current post categories in reverse chronological order. Each category is identified by its name, label (how it appears on the blog), its description, the complete time stamp (YYYY-MM-DD HH:MM/:SS) for when it was created, and the complete timestamp for when the category was last updated.

New categories can be created via the **new** button at the top of the Post Categories page.

.. _admin-users:

Users
-----

The Users List page shows a list of all current users account on the WPHP site. The list is organized in alphabetical order. Each entry is identified by the user's full name, email address, institution and :ref:`role <admin-users-roles>`.

.. _admin-users-create:

Create User
^^^^^^^^^^^

New users can be created via the **new** button at the top of the User List screen.

An email is required to create a user account. A full name and institution may also be provided. The account *must* be enabled by an administrator before the user has access to their account. The account may be enabled when creating the user account by checking the **Account Enable** box. It can also be enabled at a later time by :ref:`editing the user <admin-users-edit>` The users privileges may be assigned by selecting a :ref:`user role <admin-users-roles>`.

.. note:: After creating an account, a user with admin privileges must assign the new user a default password by selecting the user in the :ref:`Edit User <admin-users-edit>` menu and selecting the **password** button.

.. _admin-users-edit:

Edit User
^^^^^^^^^

Individual user accounts may be edited by selecting them from the User List page. The User page display information about each user including the user's email, full name institution, whether the account has been enabled or not, the user's last login (YYYY-MM-DD HH:MM\:SS), and the :ref:`privileges <admin-users-roles>` the user has.

The user edit screen can be accessed via the **edit** button above the user information. It is identical to the create user screen and allows editing for the user's email, full name, institution, whether the account has been enabled and the user's role.

From the user screen, the user's password can be change via the **password** button above the user's information The password change page will prompt for a new password and a confirmation of the new password. Upon clicking update, the password for that user will be changed.

The user account may also be deleted from the user page via the **delete** button above the user information. A dialogue box will confirm if you want to delete the user.

.. _admin-users-roles:

User types/roles
^^^^^^^^^^^^^^^^

.. _admin-users-roles-admin:

ROLE_ADMIN
  ROLE_ADMIN users have access to the entire admin control panel. They can update and moderate :ref:`comments <admin-comments>`, view :ref:`feedback <admin-feedback>`, add and update :ref:`blog posts <admin-blog-posts>`, and add and update :ref:`users <admin-users>`.
  
.. _admin-useres-roles-blog:

ROLE_BLOG_ADMIN
  ROLE_BLOG_ADMIN users can add and update content to the :ref:`blog <admin-blog-posts>`.

.. _admin-users-roles-comment:

ROLE_COMMENT_ADMIN
  ROLE_COMMENT_ADMIN users can update and publish :ref:`comments <admin-comments>`.

.. _admin-users-roles-content:

ROLE_CONTENT_ADMIN
  ROLE_CONTENT_ADMIN is reserved for users who have access to edit content. These permissions are not enabled at this time.

.. _admin-users-roles-user:

ROLE_USER
  All user accounts are automatically assigned the ROLE_USER role.

