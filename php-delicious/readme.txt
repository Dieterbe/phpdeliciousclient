PhpDelicious - a library for accessing the del.ico.us API
=========================================================

Overview
--------

Support for:

* PHP 4/5
* adding, updating, deleting posts
* viewing posts, post date statistics
* adding, deleting, renaming tags
* adding, updating, deleting bundles
* returing last update date
* returning error numbers and messages
* caching and automatic protection against throttling of API
* HTTP requests through CURL or fopen (automatic selection from supported methods)
* Backup and restore to/from MySql (implemented in example files)

Site URL
--------

http://www.ejeliot.com/pages/php-delicious

License
-------

Software License Agreement (BSD License)

Copyright (C) 2005-2006, Edward Eliot.
All rights reserved.
   
Redistribution and use in source and binary forms, with or without
modification, are permitted provided that the following conditions are met:

* Redistributions of source code must retain the above copyright
  notice, this list of conditions and the following disclaimer.
* Redistributions in binary form must reproduce the above copyright
  notice, this list of conditions and the following disclaimer in the
  documentation and/or other materials provided with the distribution.
* Neither the name of Edward Eliot nor the names of its contributors 
  may be used to endorse or promote products derived from this software 
  without specific prior written permission of Edward Eliot.

THIS SOFTWARE IS PROVIDED BY THE COPYRIGHT HOLDER AND CONTRIBUTORS "AS IS" AND ANY
EXPRESS OR IMPLIED WARRANTIES, INCLUDING, BUT NOT LIMITED TO, THE IMPLIED
WARRANTIES OF MERCHANTABILITY AND FITNESS FOR A PARTICULAR PURPOSE ARE
DISCLAIMED. IN NO EVENT SHALL THE COPYRIGHT OWNER OR CONTRIBUTORS BE LIABLE FOR ANY
DIRECT, INDIRECT, INCIDENTAL, SPECIAL, EXEMPLARY, OR CONSEQUENTIAL DAMAGES
(INCLUDING, BUT NOT LIMITED TO, PROCUREMENT OF SUBSTITUTE GOODS OR SERVICES;
LOSS OF USE, DATA, OR PROFITS; OR BUSINESS INTERRUPTION) HOWEVER CAUSED AND
ON ANY THEORY OF LIABILITY, WHETHER IN CONTRACT, STRICT LIABILITY, OR TORT
(INCLUDING NEGLIGENCE OR OTHERWISE) ARISING IN ANY WAY OUT OF THE USE OF THIS
SOFTWARE, EVEN IF ADVISED OF THE POSSIBILITY OF SUCH DAMAGE.

Change Log (started 11/11/2006)
-------------------------------

13/01/2007:
   Modified cache to make it username specific
11/11/2006:
   Added support for notes field in results returned
   Added LastErrorString() method
   Replaced LastError() method with LastErrorNo() but kept original as alias for backwards compatibility
   Added examples for exporting/importing posts to/from MySql (see examples folder)
   Added example to print a simple table of results (see examples folder)
   
Public Methods
--------------

LastErrorNo()
LastError (alias for LastErrorNo())
LastErrorString()
GetLastUpdate()
GetAllTags()
RenameTag()
GetPosts()
GetRecentPosts()
GetAllPosts()
GetDates()
AddPost()
DeletePost()
GetAllBundles()
AddBundle()
DeleteBundle()

Full documentation for these methods to follow. For now see examples and source for parameters and usage.