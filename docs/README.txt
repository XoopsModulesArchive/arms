ArMS README.txt
================================================================================

 01. Disclaimer
 02. What is ArMS?
 03. Key features
 04. Version info
 05. How to install?
 06. How to update?
 07. Contact info

0.1 Disclaimer
==============

This software is provided "as-is".
No warranty of any kind is expressed or implied.
The author will not be liable for data loss, damages, loss of profits or any
other kind of loss while using or misusing this software.
License: GPL

0.2 What is ArMS?
=================

ArMS (Artical Menagement System) is XOOPS 2 module for menaging multipage
articals and tutorials. Main idea was to privide a good solution for creating
XOOPS 2 driven tutorial sites.

0.3 Key features:
=================

 - 4 level data structure
   - categories - sections - articles - pages
 - article commenting
 - article rating
 - extra permissions (coauthoring)
 - crossposting (one article in more than one section)

0.4 Version info
================

Version: 0.4
Info:    Minor reconstructions (some bugs fixed, new preferences and so on)
Changes: View CHANGELOG.txt

0.5 How to install?
===================

Copy arms directory and all of its files to xoops/modules dir. Go to admin and
in system admin menu click on Modules option. Find ArMS icon in list of
available but not installed modules and click on Intall image (in Action
column). Then click on Intall button. That should be all...

0.6 How to update?
==================

Replace old ArMS files with new ones...

If you use ArMS 0.3 just update ArMS from XOOPS Modules page. There is no data
definition changes...

If you use ArMS 0.2 update ArMS from XOOPS Module page. Then select Update
from ArMS menu and select ArMS 0.2 -> ArMS 0.4 option. Click on go. If there is
no errors everithing is good. In case of any error take a look at
update02to03.sql file in SQL directory. Rename tables (add prefix) and run that
file from phpMyAdmin or any other MySQL management tool.

0.7 Contact info
================

ilija_studen@yahoo.com

================================================================================
                            (c) 2003. by Ilija Studen
