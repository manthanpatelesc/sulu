!function(){"use strict";define([],function(){return{getButtons:function(a){return[{name:"saveWithDraft",template:{icon:"floppy-o",title:"public.save",disabled:!0,callback:function(){a.sandbox.emit("sulu.toolbar.save","publish")},dropdownItems:{saveDraft:{},savePublish:{},publish:{}}}}]},getDropdownItems:function(a){return[{name:"saveDraft",template:{title:"sulu-document-manager.save-draft",callback:function(){a.sandbox.emit("sulu.toolbar.save","draft")}}},{name:"savePublish",template:{title:"sulu-document-manager.save-publish",callback:function(){a.sandbox.emit("sulu.toolbar.save","publish")}}},{name:"publish",template:{title:"sulu-document-manager.publish",disabled:!0,callback:function(){a.sandbox.emit("sulu.toolbar.save","publish")}}}]}}})}();