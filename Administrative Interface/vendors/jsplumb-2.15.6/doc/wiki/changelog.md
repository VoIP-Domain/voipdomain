## 2.15.5

December 14th 2020

- Extra piece of cleanup for Endpoint/Connection canvases to avoid leaking memory via element refs.

## 2.15.4

December 12th 2020

- Added missing `addOverlay` and `removeOverlay` methods on Connection in index.d.ts

## 2.15.3

December 8th 2020

- Fix for infinite loop in `empty()` method (issue #997)

## 2.15.2

December 1st 2020

- Further minor updates to index.d.ts, specifically the addition of a couple of missing return types.

## 2.15.1

November 27th 2020

- A minor release that includes updates to index.d.ts to include methods/types for working with Groups

## 2.15.0

October 26th 2020

### Breaking

- `revalidate` now supports only a single element as argument, and returns a `RedrawResult` object, which contains a list of Connections
and Endpoints that were repainted as a result of the call.

### Non-breaking

- When element rotated, fix issue where the original x/y locations for an anchor were being overwritten


## 2.14.7

October 15th 2020

- additional unit testing for the setTarget method
- added `destroy` method to jsPlumb class, which performs a reset and also sets a bunch of closure-wide variables to null.
- switched a few functions from using closures to using members on the Anchor class
- added support for rotating elements, using the `rotate(element:string|Element, degrees:number)` method of jsPlumb.

## 2.14.6

September 4th 2020

- Internal changes to reference a Router for various methods rather than an AnchorManager. There is no functional change; this work was undertaken to assist in the 4.x rewrite.

## 2.14.5

July 27th 2020

- Further updates to type defs for Connection and Endpoint - added setParameter/setParameters/getParameter/getParameters

## 2.14.4

July 27th 2020

- change `parameters` to `Record<string, any>` in `EndpointOptions` in type definitions
- add `parameters` to `ConnectParams` in type definitions

## 2.14.3

July 14th 2020

- Backed out the change from 2.14.0 that dispensed with taking overlay placements/stroke width into account. In some cases browsers were choosing spurious values for stroke width with this setup.

## 2.14.2

July 12th 2020

- Always use width/height of at least 1 pixel in SVG element. This is a proper fix for the issue from 2.14.0; don't use 2.14.1.

## 2.14.1

July 12th 2020

- Fixed issue with SVG elements sometimes being assigned height/width of 0, in which case overflow is ignored.

## 2.14.0

July 11th 2020

### Breaking

- Refactored connection paint code to not take overlay placements or stroke width of connector into account when computing SVG bounds.  The `.jtk-connector` class in the `jsplumbtoolkit-defaults.css` file now has `overflow:visible`, which covers this. If you are upgrading from a prior version you should ensure this style is applied to your `.jtk-connector` elements.

### Non-breaking

- `doNotFireEvent` parameter in `deleteConnection` js doc switched to `fireEvent` (issue 932)
- Internal refactoring of paint code to introduce the concept of a "router". No functional change.

## 2.13.4

July 9th 2020

- Added missing `uuid` option to `EndpointOptions` in types. No change to the code.

## 2.13.3

Jun 16th 2020

- Minor changes to demonstrations. No change to the library.

## 2.13.2

May 1st 2020

- when adding new endpoint and drawing is not suspended, ensure offset/size is always updated. 

## 2.13.1

April 12th 2020

- fix issue #924 - connections for nested child elements not recognised when parent added to a group.
- Support `ListStyle` default, for specifying endpoint/anchor of `jtk-scrollable-list` elements.

## 2.13.0

March 20th 2020

- upgrade to Mottle 1.0.1

## 2.12.14

March 2nd 2020

- faster `uuid` method implementation
- faster bezier curve length method
- issue #914 and #913 part 2

## 2.12.13

February 29th 2020

- fix issue 913 - arrow overlays disappearing when changing types

## 2.12.12

February 29th 2020

- fix issue 912 - change of element ID not recognised by makeSource when user drags with mouse.

## 2.12.11

February 28th 2020

- fix duplicated types.

## 2.12.10

February 27th 2020

- updates to groups code to handle correctly hiding connections between elements that are not direct children of the group
- several typings changes, one of which was partly duplicated, causing this version to not be something you should install.

## 2.12.9

January 30th 2020

- redraw child elements on group drag (fixes issue with group members that have associated connections that are on descendants of the child element, not the child element itself)
- add setZoom/getZoom to Typescript definitions file. 

## 2.12.8

December 11th 2019

- fixes for the sort functions used to sort edges by the continuous anchors (#893, #892, #891, #890).

## 2.12.7

November 28th 2019

- remove the ability to add nodes that are inside a group to the current drag selection.
- when a node is added to a group, it is removed from the current drag selection.

## 2.12.6

November 18th 2019

- update groups code to correctly orphan/prune multiple dragged elements

## 2.12.5

October 29th 2019

- fix issue #861, error on droppable.

## 2.12.4

October 27th 2019

- minor updates to endpoint label handling
- update `remove` method to test for parent node existence before attempting removal.

## 2.12.3

October 20th 2019

- ensure `label` from connection type is not presented to endpoint.

## 2.12.2

October 20th 2019

- minor update to the return value of the internal method used to find the point on a Bezier connector closest to some point in the viewport. 

## 2.12.1

October 19th 2019

- minor update to the return value of the internal method used to find the point on a connector closest to some point in the viewport.

## 2.12.0

September 23rd 2019

- added support for label location being specified in the `data` for some Connection, via key `labelLocation`
- added support for overridding `labelLocation` key name with user-specified key, via `labelLocationAttribute` in Label overlay options.

## 2.11.2

August 7th 2019

- added `snapToGrid()` method to jsPlumb instance.
- added `replaceEndpoint()` method to Connection.

## 2.11.1

July 18th 2019

- fixed issue in reset method introduced in 2.11.0

## 2.11.0

July 17th 2019

- updated util from Toolkit edition

- Changes to the way types are overridden/merged (not all backwards compatible):

    - By default, every key in a type overrides previous types, with the exception of `cssClass`, `events` and `overlays`. Previously, only
    `connector` would override other types.  This meant that if you used any of the array variants to specify `anchor`,  the arrays 
    would be merged and the result would be nonsense.  It also meant that `paintStyle` and its variants would be merged at a 
    fine-grained level: you could specify `strokeWidth` in a parent and then `stroke` in a child. That is no longer possible.
    
    - You can set `mergeStrategy:"override"` in a type now to indicate to jsPlumb that it should overwrite parent definitions of
    `events` and `overlays` rather than merge them with the child's values for them.
    
    - `cssClass` is still "collated" by default, ie. in a normal merge both child `cssClass` and parent `cssClass` values are
    retained. If you specify `mergeStrategy:"override"` then `cssClass` is strictly overridden and is not collated.


## 2.10.2

July 4th 2019

- Upgraded to Katavorio 1.4.8

## 2.10.1

June 25th 2019

- refactored how makeSource gets its source parameters, so that call sites can manipulate the parameters after the makeSource call.
- PR 843: reapplyTypes() for Arrow overlays ignores 'direction' parameter
- fixed an issue causing connectors to disappear when a type is applied multiple times.


## 2.10.0

June 5th 2019

- fix `rectangle` and `square` Perimeter anchors so that their orientation is correct for each face.
- add support for scrollable lists, plus a demonstration page

## 2.9.3

May 9th 2019

- Upgrade to Katavorio 1.4.5
- support "scoped root" selectors in delegated draggables

## 2.9.2

April 22nd 2019

- upgrade Katavorio to 1.4.2

## 2.9.1

March 7th 2019

- reinstate the ability to build a local copy of the docs

## 2.9.0

January 28th 2019

- update `ConnectParams` to include `connector?:ConnectorSpec` in typings file.

## 2.8.8

December 28th 2018

- removed unused method, and its usage from tests

## 2.8.7

December 28th 2018

- update types file to add overlays to ConnectParams

## 2.8.6

December 11th 2018

- better decision making when determining if a connection is detachable or not.
 
## 2.8.5

December 7th 2018

- added missing `findClosestPointOnPath` method to Bezier segment.
- added support for finding the intersection of a line and a segment.

## 2.8.4

November 2nd 2018

- do not merge `connector` declarations when merging connection types. The child value overwrites the parent now.
- issue #794 - use `jsPlumb.addClass(div,...)`` instead of `div.className=` - this was causing an error when the custom overlay was an SVG element

## 2.8.3

October 28th 2018

- fixed a repaint issue with Continuous anchors

## 2.8.2

October 18th 2018

- remove animated paths from Flowchart demo, as IE does not support that CSS.

## 2.8.1

October 18th 2018

- Upgrade to Katavorio 1.2.0

## 2.8.0

August 31st 2018

- Upgrade to Mottle 1.0.0, which doesn't use document.createTouch/document.createTouchList, as these methods are not supported
in latest Chrome and are becoming obsolete in all browsers. If you cannot upgrade to this version of jsPlumb and you're finding problems
in Chrome on touch devices, there are shims available in the Mottle project on Github.

## 2.7.19

August 28th 2018

- proper cleanup for arrow overlay

## 2.7.18

August 28th 2018

- Fixed an issue with overlays not being removed from every cache during removeOverlay call on overlay component.

## 2.7.17

August 28th 2018

- Minor change to the segment paint code, to allow for animation effects to be used.

## 2.7.16

August 20th 2018

- Upgrade to Katavorio 1.0.0

## 2.7.15

August 11th 2018

- Issue 582: ConnectionsDetachable does not allow dragging multiple connections from endpoints when false

## 2.7.14

August 10th 2018

- Group's orphanAll method now returns a map of element positions for the previous child elements.
- Group's removeAll method works without throwing an error now.

## 2.7.13

July 27th 2018

- PR #769: refactor DragManager's `register` method to avoid computing parent offsets when unnecessary.

## 2.7.12

July 25th 2018

- changed a method declaration whose format caused Angular's optimizer to fail (when running an Angular production build)

## 2.7.11

July 23rd 2018

- remove unnecessary double firing of the group:addMember event.

## 2.7.10

July 22nd 2018

- fix for setting class name of SVG element in IE11 - it doesnt expose `classList`. The reported error was not fatal but now
we test if `classList` is null before accessing it.

## 2.7.9

June 24th 2018

- moved Defaults off the jsPlumbInstance prototype and into the class itself. Prevents global variable effect.
- update the internals of creating Connectors to make it easier to add a custom connector.

## 2.7.8

June 22nd 2018

- additions to Typescript typings file
- group:addMember and group:removeMember events optionally include the other group involved when the event was fired as the result of
  a member changing groups.

## 2.7.7

June 21st 2018

- ensure 'rank' is passed through to drop library when present
- separate out unit tests for drag/drop and for groups.

## 2.7.6

June 20th 2018

- replaced incorrect call to detachAllConnections in demo to deleteConnectionsForElement
- fixed docs to change references from `detachAllConnections` to `deleteConnectionsForElement`

## 2.7.5

June 17th 2018

- added toggleClass method to jsPlumb

## 2.7.4

June 14th 2018

- fix for cssClass being ignored in `addEndpoint` method (PR 750)
- minor updates to the Typescript typings file.

## 2.7.3

May 27th 2018

- switched util back to an older method of declaring it on the window.
- removed bower.json

## 2.7.2

May 25th 2018

- browser util assumes 'window' as root

## 2.7.1

May 25th 2018

- added Connector to the Defaults interface in the TS typings file (#744)

## 2.7.0

May 22nd 2018

- jsPlumb.extend now copies the __proto__ chain too. 
- Remove old site code. We manage the site in a different project now.
 
## 2.6.12

May 15th 2018

- improvement to the calculation of corner radius when segments are short in a Flowchart connector
- upgrade to Katavorio 0.28.0

## 2.6.11

- slight refactor of AnchorManager to put a commonly used method on the prototype.

## 2.6.10

- documentation updates

## 2.6.9

March 16th 2018

- removal of unused 'editable' stubs. No functional change.
 
## 2.6.8

February 24th 2018

- upgrade to Katavorio 0.26.0
- added unbindDraggable and unbindDroppable methods

## 2.6.7

February 8th 2018

- add the ability to lock a Continuous Anchor's current 'axis' (ie. it can choose left/right OR top/bottom).

## 2.6.6

February 8th 2018

- add the ability to lock a Continuous Anchor's current face.
- upgrade to Katavorio 0.25.0

## 2.6.5

February 2nd 2018

- add support for `connectorOverlays` to `makeSource` method
- upgrade to Katavorio 0.24.0

## 2.6.4

January 28th 2018

- trivial formatting issue required by upstream collation mechanism in Toolkit 

## 2.6.3

January 26th 2018

- Updates to Typescript typings files

## 2.6.2

January 24th 2018

- `getInstance` method in index.d.ts returns `jsPlumbInstance` instead of `any` (issue 680)

## 2.6.1

January 24th 2018

- comment out a block of code instead of using 'if false' to prevent its execution (issue 707)

## 2.6.0

January 20th 2018

- switch from lazy loading of connector types to direct registration. In certain Webpack scenarios the lazy loading was causing connectors to not be found.

## 2.5.14

January 19th 2018

- improvements to the .d.ts file.

## 2.5.13

January 14th 2018

- minor internal refactoring of the flowchart connectors
 
## 2.5.12

January 4th 2018

- refactor the method that chooses which connection is being dragged on some endpoint that has more than one connection. no functional change.
- update to index.d.ts to fix compile error

## 2.5.11

December 23rd 2017

- doc updates to include `deleteConnection` method on jsPlumbInstance.

## 2.5.10

- fix for connections lost when group collapsed (issue 694)

## 2.5.9

- dont default to false for 'directed' on a connection; allow it to be undefined if not set.

## 2.5.8

7th December 2017

- minor updates to the way anchors and connectors are set to fix a couple of small bugs related to types.

## 2.5.7

22nd October 2017

- update Katavorio to version 0.22.0

## 2.5.6

18th October 2017

- fix for an issue seen in Chrome where setting the className of an SVG element does not result in the element's classList being updated.

## 2.5.5

8th October 2017

- issue 675, connector not changed in setType/addType

## 2.5.4

7th October 2017

- fix issue with drag containment in elements whose scroll height is larger than their client height.

## 2.5.3

5th October 2017

- expose Connection to anchor at compute time.

## 2.5.2

4th October 2017

- upgrade to Katavorio 0.20.0.  Fixes issue #618, in which constraining drag to a grid did not work for anything other than
a grid of size [10,10]

## 2.5.1

28th August 2017

- fix for the setType method: if the new type had a different connector, arrow overlays were not being transferred.

## 2.5.0

22nd August 2017

- removed the old changelog.txt, which hadnt been used since 2.0.3 and was probably confusing matters.
- fixed stale references to various detach methods which were renamed a few versions ago.
- EventGenerator was updated to fire each event on a separate tick of the event loop. Previously, if an event was fired during
the callback to a previous event, the second event handler was executed in its entirety before the original event handler. 
Enqueuing events that are fired during the event loop and running them after each tick prevents this from happening.

## 2.4.3

20th June 2017

- update documentation to remove references to the now defunct jQuery flavour of jsPlumb.
- SVG elements use SVG namespace rather than XHTML

## 2.4.2

10th May 2017

- update calls to drag manager to handle the case that one is not set

## 2.4.1

10th May 2017

- Support `collapsed` as a parameter to the `addGroup` method, which specifies that a Group be initially collapsed.

## 2.4.0

8th May 2017

Several methods and parameters have been renamed to better reflect their function:

#### jsPlumbInstance

  - `detachAllConnections` renamed to `deleteConnectionsForElement`
  
  - `detach` renamed to `deleteConnection`. `detach ({source.., target:...})` can be achieved with `select({source:..,target:..}).delete()`
  
  - `detachEveryConnection` renamed to `deleteEveryConnection` 
  
  - `connect` method: the `deleteEndpointsOnDetach` parameter is now `deleteEndpointsOnEmpty`
  
  - `getEndpoints` method returns empty list when none found now, not null.
  
  - `select` method: the return value of this now has a `delete` method, instead of `detach`.
  
  - `selectEndpoints` method : the return value of this now has a `deleteEveryConnection` method, instead of `detachAll`.  
  
#### Endpoint
  
  - `detach` method removed
  - `detachAll` renamed to `deleteEveryConnection`
  

## 2.3.6

1st May 2017

- fix for the filenames to the toolkit css files (they are lower case now)
 

## 2.3.5

24th April 2017

- Reordered the sequence of events when a node is dragged out of a group.

## 2.3.4

23rd April 2017

- fixed a reference to dragManager which short-circuited the lazy loading. 

## 2.3.3

23rd April 2017

- update drag manager offsets during revalidate

## 2.3.2

22nd March 2017

- upgrade Katavorio to version 0.19.2, to fix a minor issue with droppables not being cleared after mouseup.

## 2.3.1

15th March 2017

- fixed possible NPE when adding child to group
- do not override endpoints in connect call with endpoints from type if endpoints are specified.

## 2.3.0

14 February 2017

- removed version extension from built file names and made filenames lower case : jsplumb.js, jsplumb.min.js
- npm pack the project at build time (to local file jsplumb.tgz)
- reinstate bower.json in this project 
- reorganise files used for building
- ensure element connections repainted correctly after element added to group 
- fix addToGroup to support multiple elements
- switch to imports using npm (jsBezier, katavorio, biltong, mottle) rather then included in lib dir of project

## 2.2.10

13 January 2017

- addition of `version` member to built JS. current version is now accessible via `jsPlumb.version` (or `.version` on 
an instance).

## 2.2.9

13 January 2017

- limit files that are published to npm to just js, minified js and css
- remove version number from built filename

## 2.2.8

20 December 2016

- disallow addition of some node to a group in which it is already a member
- remove node from its current group (if there is one) upon addition to new group.
- fix for drag manager not being called to re-register a node if it was added to a new group
programmatically and not via drag/drop

## 2.2.7

10 December 2016

- fixed an issue with setting a blank label on a Label overlay.

## 2.2.6

05 November 2016

- fix deployment issue in 2.2.6


## 2.2.5

- upgrade to mottle 0.7.4 

## 2.2.4

- no code change from 2.2.3. built to provide initial build in the bower-jsplumb repo.

## 2.2.3

- add support for connectionType to makeTarget call (to allow multiple types of target)

## 2.2.2

- corrected deployment issue in 2.2.1. 2.2.1 does not do what it says; it is an impostor.

## 2.2.1

- upgrade to Katavorio 0.18.0; now `rank` is supported in `dropOptions` to `makeTarget` or `addEndpoint`. Use this when
you want to make a Group container a connection target but you want to give prededence to Nodes inside the Groups.

- set drop rank for Group elements automatically (if user does not provide one) so that Nodes receive drop events before
Groups.


## 2.2.0


- Overhaul of keys used in paintStyle and hoverPaintStyle objects:

  strokeStyle   -> stroke
  fillStyle     -> fill
  lineWidth     -> strokeWidth
  outlineColor  -> outlineStroke
  outlineWidth  -> outlineWidth     (yes, unchanged)
  
  
- All classnames changed from `jsplumb-` prefix to `jtk-`, to bring them into line with the prefix used by the Toolkit edition.
  
- support webpack
- add webpack demo page
- upgrade to Mottle 0.7.3
- upgrade to Katavorio 0.17.0
- straight connectors extracted to a separate JS file for dev
- added disable/enable functionality example to sources and targets demo

## 2.1.6

- setTargetEnabled/setSourceEnabled now return the previous value of the enabled state.
- disabled source/target elements get a `jtk-source-disabled` or `jtk-target-disabled` class added.
- issue 552 - in place endpoint painted in wrong location during connection drag
- issue 554 - after drag, connections to a node inside a group are positioned incorrectly.


## 2.1.5

- issue 533 - Dragging multiple nodes causes incorrect connectors position
- `reset` method sets hover suspended flag to false now.



## 2.1.4

- issue 530 - Further fix related to issue 530, in which elements that had connections prior to being added to a group
were sometimes getting an offset applied when dragging. The fix for this removed some code that was put in for issue 231, 
but it turns out the fix for issue 231 had broken somewhere along the line and this change set that right too.


## 2.1.3

- issue 530 - Element with existing connections being added to Groups.
- issue 526 - bower version incorrect

## 2.1.2

- issue 523 - Endpoint click registration problems
- issue 522 - Groups documentation

## 2.1.1

- bugfix for groups: element exposed now via getEl method, not directly as el. 

## 2.1.0

- 'elementDraggable' event now fired whenever an element is made draggable via the `draggable` function
- add support for 'groups' - elements that can contain other elements, and which are collapsible.
- upgrade to Mottle 0.7.2. a few fixes for event delegation.

- upgrade to katavorio 0.17.0
- upgrade to mottle 0.7.2
- upgrade to jsBezier 0.8
- upgrade to Biltong 0.3

ISSUES

- 483 - srcElement undefined in Firefox
- 484 - changed a couple of variables refs so that they are not reserved words 


## 2.0.6

- add `connectionAborted` event, fired whenever a new connection is abandoned before being dropped on an endpoint or
target. also fired if `beforeDrop` interceptor returns false.

- fixed docs for `connectionDetached` to call out the fact that it is not fired when a connection is abandoned.
 
 ISSUES
 
- 472 - Pending connections do not fire the documented events
- 469 - Scopes not applied to new drag & drop connections
- 452 - Why "connection.scope" property cannot get scope value ?


## 2.0.5

- Refactor Bezier and StateMachine connectors to extend common AbstractBezierConnector parent. This means Bezier
 connectors now support loopback connections.
 
- add support for loopback connections to Flowchart connector (issue 457).
 
ISSUES
 
- 458 connectionDetached is fired the first time only

- 457 'Flowchart' connector: loopback connections broken

- 451 cannot bind events to arrow overlays

- 446 addClass and removeClass on Endpoint and Connection now also add/remove class from their overlays, by default. This
 can be overridden by providing 'true' as the second argument to the addClass/removeClass methods.
 
- 434 wrong arrow drawing (offset) when creating a connection on IE9 
 

## 2.0.4

- upgrade to Katavorio 0.13.0
- upgrade to Mottle 0.7.1
- add `droppable` method to jsPlumbInstance: the ability to make _elements_ droppable. Not connections or endpoints - DOM 
elements.
- fixes for offset calculation when dragging a nested element.

## 2.0.3

### Issues

- 444 - maxConnections not honoured in makeSource

### Backwards Compatibility

- `removeFromPosse` now requires the ID of the Posse, since the new Katavorio version supports multiple Posses
per element.

### New Functionality

- Upgrade to Katavorio 0.12.0, with support for multiple Posses and active/passive elements in a Posse.
- `removeFromAllPosses(element)` method added.


### Miscellaneous

- Fixed an issue in which overlays on Endpoint types were not being converted to 'full' syntax upon registration. This was
an internal issue that could manifest in user code occasionally.

- We now ensure drag scope is set on an element when source scope changes, even though the code can derive source scope
when the user begins to drag. The Toolkit edition makes use of this new update.



    
## 2.0.2

Fix issues with CSS class documentation.

## 2.0.1

Bugfix release: connectionDetached event was no longer firing.

## 2.0.0

### Backwards Compatibility

- Removal of the VML renderer. IE8 no longer supported.
- All class names such as `_jsPlumb_connector` renamed to, for example, `jsplumb-connector`.
- makeSource and makeTarget require explicit anchor/endpoint parameters: they do not source these things
  from the jsPlumb Defaults.


### New Functionality

- makeSource now supports multiple registrations per element, keyed by the `connectionType` parameter.  You can configure 
elements to be connection sources for different connection types, and also when you call `connect` with a `type` 
parameter that matches a `makeSource` registration, that type will be used.
- new connection drag: if the type of connection is known, that type's target endpoint is now used.
- addition of support for `dragProxy` to endpoint/makeSource: an endpoint spec defining what the drag endpoint should 
look like when dragging a new connection. The existence of a `dragProxy` will override any other behaviour (such as the 
behaviour discussed in the point above)
- addition of "posses" - groups of elements that should always be dragged together.
- when dragging a new connection, jsPlumb now uses as the source endpoint a true representation of what the endpoint
    will be if a connection is established. Previous versions just used a static, in-place, endpoint.


## 1.7.10

### Changes between 1.7.9 and 1.7.10

- Small update to getOffset to make it return the correct value if the input element was the container itself.
- Small update to animation to fix incorrect falsey check.
- Documented the `on` method of a `jsPlumbInstance` in the API docs.
- `on` and `off` event registration methods now return the current jsPlumb instance
    
    
## 1.7.9

### Changes between 1.7.8 and 1.7.9

- No more jQuery flavour. Vanilla jsPlumb is the only jsPlumb, and as such, has been renamed to simply `jsPlumb-1.7.9.js`.
- First version of jsPlumb to be published to npm.
- Addition of getManagedElements method. Returns a map of all the elements the instance of jsPlumb is currently managing.

#### Issues

- **421** svg gradient elements not cleaned up properly

## 1.7.8

### Changes between 1.7.7 and 1.7.8

#### Issues

- **381** -  instance.detach(connection) will detach source endpoint as well
- **419** -  endpoints not cleaned up properly when connection converted to looback to endpoints not cleaned up properly when connection converted to loopback
- **420** - Image endpoint not cleaned up correctly


## 1.7.7

### Changes between 1.7.6 and 1.7.7


#### Issues

- **408** - setIdChanged doesn't correctly handle element sources/targets
- **410** - setConnector (whether applied via type or directly) removes custom css classes of other types
- **412** - Endpoint style cannot be transparent 
- **415** - Unnecessary endpoint may be created at when drag and drop endpoint from one node to another.

## 1.7.6

### Changes between 1.7.5 and 1.7.6

A minor bugfix release, with a few new options for controlling connection detachment (and one small backwards 
compatibility issue to be aware of)

#### Backwards Compatibility

- All versions of jsPlumb prior to 1.7.6 would fire `beforeDetach` for both new Connection drags and also 
dragging of existing Connections. As of 1.7.6 this latter behaviour has been moved to the `beforeStartDetach` 
interceptor.

#### New Functionality

-  `revalidate` now supports the same arguments as repaint - an ID, an Element, or a list-like
    object (such as the results of $(..) or document.querySelectorAll)

- added `beforeStartDetach` interceptor: a function that is called before an existing connection is dragged off of 
one of its endpoints, and which can return false to cancel the drag.

- The `unbind` method on various objects (jsPlumbInstance, Connection, Endpoint to name a few) now supports passing a 
Function to be unbound, rather than just some event name.

- Connectors now have a `getLength` function, which returns their length in pixels. To access from a Connection,
      you need to first get the connector: `someConnection.getConnector().getLength()`

#### Issues

- **350** - recalculateOffsets not working
- **353** - multiple select disabled
- **367** - rendering and drag/drop errors when parent element scrolled
- **369** - unbinding events
- **383** - jsPlumb.setDraggable fails for getElementsByClassName return value
- **392** - onMaxConnections jpc isn't defined
- **402** - offset update cache
- **404** - statemachine demo makes ghost endpoints

## 1.7.5

### Changes between 1.7.4 and 1.7.5

A minor-ish release; no changes to the API. Some refactoring of JS and of CSS. But one notable thing is that touch events on Windows touch laptops are working now (in Chrome and IE; FF seems to still have issues)

#### Backwards Compatibility

- The jQuery flavour was removed from the `main` section in `bower.json`.

#### Issues

- **295** - draggable not working in chrome
- **340** - Draggable stop event doesn't get called on all elements when dragging multiple elements
- **341** - Add possibility to change z-order of the "inPlaceCopy" endpoint.
- **344** - add getUuids method to Connection
- **345** - Error when two linked objects are with exactly same position

## 1.7.4

### Changes between 1.7.3 and 1.7.4

#### Issues

  - **237** - scroll is ignored in offset calculations
  - **314** - jsPlumbUtil is not defined (webpack)
  - **329** - Scroll issue
  - **332** - endpoint label not working in newest version
  - **333** - ReattachConnections not working when a connection is detached (jquery & vanilla 1.7.3)
  - **336** - cannot drop a connection back on the endpoint to which it was previously attached


## 1.7.3

### Changes between 1.7.2 and 1.7.3

Predominantly a minor bugfix release, this version brings a degree of uniformity to the behaviour of elements configured with `makeSource` and `makeTarget`, and is a recommended upgrade if you are currently using any other 1.7.x version.

#### New Functionality

- There is a new interceptor in this release: `beforeDrag`.  You can use it to abort dragging a connection as soon as it starts, and also to supply the initial data for a Connection that uses a parameterized type.
- Added `jsPlumb.empty` function: remove child content from a node, including endpoints and connections, but not the element itself.


#### Backwards Compatibility

- The `doWhileSuspended` method has been aliased as `batch`, and `doWhileSuspended` is now deprecated, to be removed in version 2.0.0.

#### Issues

  - **187** - jsPlumb.draggable() doesn't work with forms
  - **281** - beforeDetach not triggered by `jsPlumb.detachAllConnections`
  - **287** - Cannot drop source of connection on makeTarget element after 1.6.4
  - **289** - Cannot prevent drop of source edge using beforeDrop on nested makeTarget elements
  - **297** - Distinguish drag\click for Vanilla jsPlumb
  - **298** - Fix for using library inside shadowDom (e.g. Polymer etc.)
  - **307** - Setting Container multiple times fires events multiple times
  - **311** - addType resets overlays
  - **313** - setContainer does not work when container has overflow: scroll;
  - **315** - setConnector removes existing overlays
  - **317** - Docs incorrectly refer to "mouseenter"
  - **326** - Connections not updating position - (detach, delete, readd, reconnect)
  

## 1.7.2

### Changes between 1.7.1 and 1.7.2

- Reverted a minor bugfix introduced by the fix for issue 276
- Updated continuous anchors to allow for several Continuous anchors to be in use on the one element.

## 1.7.1

### Changes between 1.7.0 and 1.7.1

#### Issues

- **276** - TypeError on dragging empty target endpoint


## 1.7.0

### Changes between 1.6.4 and 1.7.0

#### Backwards Compatibility

- Perhaps the biggest change between 1.6.4 and 1.7.0 is that YUI and MooTools are no longer supported.  It is recommended you use vanilla jsPlumb now. jQuery is still supported but it is neither as fast nor does it have as many features as vanilla jsPlumb.

- The `parent` argument to the `makeSource` function is no longer supported. It was being kept because neither YUI nor MooTools have the ability to support a drag filter, but now that those libraries are not supported this feature has been removed.  The `filter` approach is much more powerful.

#### New Functionality

Perhaps not strictly new functionality, but shiny enough to warrant being associated with the word "new", is the fact that jsPlumb 1.7.0 is considerably faster than any previous version.  A rough comparison: the default settings for the load test in jsPlumb generate 360 connections in total between 10 elements. in 1.6.4 this test averages about 1600ms in Chrome on a Mac.  In 1.7.0 that number is about 600ms on the same computer.

#### Issues

- **178** - Detachable endpoints: different behaviour between connect() and mouse-based connections
- **214** - Endpoint stays visible when should be terminated (right mouse button)
- **242** - Distinguish drag\click for Vanilla jsPlumb
- **245** - reinstate isConnectedTo method on Endpoint
- **246** - outlineColor ignored when gradient defined in paintStyle  
- **248** - dynamic anchor create fail
- **257** - allow for the scope of a makeSource element to be changed
- **258** - Typo in documentation: s/container/Container 
- **260** - isSource and isTarget usage with makeSource and makeTarget causes broken connections
- **261** - Two target endpoints close to each other: "TypeError: Cannot read property '0' of null"
- **262** - hoverPaintStyle only works for the first connection (maxConnections > 1) 
- **263** - TypeError: conn.endpoints is null
- **267** - continuous anchors with faces set do not paint on selected faces when not connected to anything
- **268** - Endpoint "Blank" generates endpoint with class "undefined"
- **269** - Source endpoint does not/cannot respect uniqueEndpoint setting
- **270** - Support `endpointStyle` in args to addEndpoint and makeSource/makeTarget

## 1.6.4

### Changes between 1.6.3 and 1.6.4

#### Backwards Compatibility

- No issues

#### New Functionality

- Connection types support 'anchor' and 'anchors' parameters now.

#### Miscellaneous

- YUI adapter now sets a 'base' url and retrieves everything via https.


## 1.6.3

### Changes between 1.6.2 and 1.6.3

#### Backwards Compatibility

- No issues

#### New Functionality

- Added optional `allowLoopback` boolean parameter to vanilla jsPlumb's `makeTarget` method. 
- When using parameterized types, unmatched values are now replaced with blank strings, rather than being left in place. For instance, if you had `label="${foo}"`, and you passed a blank 'foo' value, you used to see `"${foo}"`. Now you see `""`.
- You can set `visible:false` on an overlay spec, to have it initially invisible.
- Added `setHoverEnabled` method to jsPlumb.
- Added `clearTypes` method to Connection and Endpoint
- Connection and Endpoint types now support `cssClass` property. These are merged into an array if multiple types declare a cssClass.


### Issues

- **222** - Endpoints incorrectly calculated when the anchor faces of source/target are set to left/right
- **223** - beforeDetach not fired by jsPlumb
- **224** - endpointStyle of the jsPlumb.connect method does not work
- **227** - MaxConnections=1 console log error
- **230** - Endpoints not cleaned up after connector move
- **236** - makeTarget/makeSource drag issues
- **241** - Dropping existing connection creates an orphaned endpoint when beforeDrop returns false
- **243** - setConnector not correctly re-assigning event handler on overlays


## 1.6.2


### Changes between 1.6.1 and 1.6.2

#### Backwards Compatibility

- 1.6.2 has improved behaviour for determining what element to use as the Container. Previous 1.6.x versions defaulted to the document body, with the docs strongly recommending you set a Container. From 1.6.2, if there is no Container set when the user makes a first call to either addEndpoint, makeSource, makeTarget or connect, the Container is set to be the offsetParent of either the element being configure (in the case of `addEndpoint`, `makeSource` and `makeTarget`), or the source element, for the `connect` method.

- a consequence of this is that you can no longer manipulate `Defaults.Container` manually. Your changes will be ignored; `Defaults.Container` is referenced only in the constructor or in the `importDefaults` method. If you need access to the current Container, use the `getContainer` method.

- the order of parameters to the function `jsPlumbInstance.on` has changed, in the case that you are passing 4 parameters and using it for event delegation.  Previously, the order was `(element, filter, eventId, callback)` and now the order is `(element, eventId, filter, callback)`. This brings it into line with the order of parameters in jQuery's `on` function.  It is not very likely this will affect you: `jsPlumbInstance.on` is used internally, mostly (although it can be used to register events independently of jsPlumb if you want to use it).

### New Functionality

- The Container inferencing discussed above is both a backwards compatibility issue and also new functionality!
- added `setContainer`, to allow you to move an entire jsPlumb UI to some new parent
- added `getContainer`, to allow you to retrieve the current Container.

### Issues

- **207** - problem with absolute overlays
- **211** - setVisible(true) on hidden overlay whose connection has moved causes the overlay to repaint in the wrong place

## 1.6.1

This is a minor release in which a few issues related to zooming have been fixed.

### Changes between 1.6.0 and 1.6.1

#### Backwards Compatibility

No issues

#### Issues

- **206** Fix documentation error about jsPlumb.Defaults.Endpoints

#### New Functionality

Better handling of zooming in vanilla jsPlumb.


## 1.6.0

Version 1.6.0 is a major release of jsPlumb. With this version ships a "vanilla" version - it relies on no external libraries, and also has a few features that the other library adapters do not (see below). 

### Changes between 1.5.5 and 1.6.0

#### Backwards Compatibility

- There is no support for the canvas renderer in jsPlumb 1.6.0.
- The way in which library adapters inject their functionality into jsPlumb has changed. This will affect very few people; contact jsPlumb if you need help with this.
- All elements added by jsPlumb are appended to the current "Container", which defaults to the document body. This differs from previous versions, in which if there was no Container set then jsPlumb would append elements to the parent of a connection's source endpoint. For this reason it is now more than ever recommended that you set a Container.	 
- The `container` parameter on `addEndpoint` or `connect` calls is no longer supported.

#### Issues

  - **91**  - Old ID is being used on events after setId
  - **143** - SVG gradient fails when page url already contains a hash
  - **153** - jsPlumb.animate no longer supports jQuery selectors
  - **157** - connectionMoved event not fired (when using makeTarget)
  - **162** - Connector 'Flowchart' occurs an error.
  - **164** - makeSource fails when used in conjunction with uniqueEndpoint
  - **173** - jsPlumb.setDraggable([element_id],false); fails
  - **177** - Flowchart straight Line
  - **202** - Spurious mouse events in connector with outline
  - **203** - hoverClass on endpoints doesn't work

#### New Functionality

##### DOM Adapter

It isn't actually true to say that this adapter has no external dependencies; it actually relies on a couple of new projects written specifically for this ([Mottle](https://github.com/jsplumb/mottle) for events, and [Katavorio](https://github.com/jsplumb/katavorio) for drag/drop support. However, these dependencies are wrapped into the concatenated jsPlumb 1.6.0 JS file.

###### Multiple element dragging

The DOM adapter supports dragging (and dropping!) multiple elements at once.

###### Multiple drag/drop scopes

Also supported are multiple scopes for each draggable/droppable element.

###### Using Vanilla jsPlumb with jQuery

Even if you have jQuery in the page you can use vanilla jsPlumb; it will accept jQuery selectors as arguments. Keep in mind that you won't get jQuery selectors out of it, though - any methods that return an Element will return plain DOM Elements and you'll need to turn them in jQuery selectors yourself.

#### Miscellaneous

- Events now have `this` set correctly
- Added qUnit tests for Vanilla, YUI and MooTools adapters
- Various YUI and MooTools methods were upgraded to support passing in an element list (`setId` for one)
- Added setSource/setTarget methods, allowing you to retarget a Connection programmatically.
- Reduced the amount of functionality that is delegated to a support library
- Rewrote the way support libraries are integrated

## 1.5.5

### Changes between 1.5.4 and 1.5.5

#### Issues

- **138** - allow for connection type to be derived from connection params AND endpoint params.

## 1.5.4

### Changes between 1.5.3 and 1.5.4

#### Issues
- **105** - Blank endpoint cleanup fails
- **116** - Assign anchors wont connect
- **117** - Assign anchors fail on source
- **127** - Docs on making elements draggable should note required CSS
- **128** - expose original event on `connectionDragStop` callback
- **129** - connection event fired twice by makeTarget with parent option.

#### New Functionality

- `"Assign"` anchors now work with the `makeSource` method.
- The `connectionDragStop` event now supplies the original event as the second argument to the callback function.

#### Miscellaneous

  - fixed an issue causing SVG gradients to fail when a BASE tag is present in the document.

## 1.5.3
### Changes between 1.5.2 and 1.5.3

#### Backwards Compatibility

- The fix for issue 112 involved making a change to the circumstances under which a `connectionDetached` event is fired. When you drag the source or target of an existing connection to some other endpoint, `connectionDetached` is no longer fired. Instead, a `connectionMoved` event is fired, containing the connection that was moved, the index of the endpoint that changed (0 for source, 1 for target), and the original and new source and target endpoints.

#### Issues

- **77** - Endpoint types should support Anchor parameter         
- **88** - reinstate labelStyle parameter on Label overlay.
- **90** - overlay setVisible not working (SVG/VML)
- **95** - Nested element positions not updated
- **100** - add setParent function
- **101** - JS error when detaching connection during connection callback
- **103** - IE8: connector hide does not hide overlays or background lines
- **107** - remove the necessity to set isSource/isTarget in order to make an endpoint draggable
- **108** - strange anchor orientation behaviour
- **109** - Dropping new connections on overlapping elements leads to crash after connection is deleted
- **111** - Absolute positioned arrow in wrong location
- **112** - Deleting a connection after changing its source endpoint causes failure.
- **113** - IE8 - state machine - loops are not displayed

#### New Functionality
- A setParent function was added. jsPlumb changes the parent of some element and updates its internal references accordingly (issue 100).
- Endpoint types now support the anchor parameter (issue 77)
- The `labelStyle` parameter on Label overlays has made a comeback (issue 88). The argument went along the lines of it being useful if you wanted to programmatically generate a label style.
- jsPlumb now automatically updates the internal offsets of some element that has draggable children (obviating the need for you to call `recalculateOffsets` yourself).
- When making a programmatic connection to an endpoint that was not marked `isSource:true` or `isTarget:true`, if the connection is detachable then the endpoint is made draggable, in order to allow users to drag the connection to detach it. Connections dragged off of source or target endpoints in this way can be dropped back onto their original endpoint or onto other endpoints with the same scope, but you cannot subsequently drag a new connection from an endpoint that has been made draggable by this method.
- `connectionMoved` event added. This is fired whenever the source or target of an existing connection is dragged to some other Endpoint.


#### Miscellaneous

- An issue was fixed that was preventing the ability to supply a dynamic anchor with parameters, eg

    `[ [ [ 1,0,0,1], [1,1,1,1] ], { selector:function() { ... } } ]`


## 1.5.2
### Changes between 1.5.1 and 1.5.2

#### Backwards Compatibility

- Issue 86, fixed in 1.5.2, changes the priority in which parameters are applied to a connection. The documentation has always stated that source takes priority, but in fact the code was the other way round, with target taking priority. Now source does take priority.

#### Issues

- **84** - jsPlumb 1.5.1 Arrow Disappears on IE8 when connector is straight
- **85** - dragging target endpoints created by makeTarget not working
- **86** - Connection parameters override order

#### Miscellaneous

- An issue that caused the SVG renderer to paint overlays before the connector was ready when the types API was used was also fixed.

## 1.5.1
### Changes between 1.5.0 and 1.5.1

#### Issues

- **81** - Uncaught TypeError: Cannot read property 'uuid' of null
- **82** - Blank endpoint doesn't cleanup properly
- **83** - for connections made with makeTarget originalEvent is not set

## 1.5.0
### Changes between 1.4.1 and 1.5.0

Release 1.5.0 contains several bugfixes and one or two minor enhancements, but the biggest change since 1.4.1 is the way jsPlumb handles inheritance internally - it has switched from a 'module pattern' architecture to a prototypal-based setup.  The module pattern is good for information hiding, but it makes objects bigger, and its far easier to leak memory with that sort of arrangement than it is with a prototypal inheritance scheme. 

The build has been switched from the original Ant build to Grunt with release 1.5.0, and with this has come the ability to 
build versions of jsPlumb that omit functionality you do not need (see [here](Build)).

  1. [Backwards Compatibility](#backwards)
  - [New Functionality](#new)
  - [Issues Fixed](#issues)
  - [Miscellaneous](#misc)

<a name="backwards"></a>
### Backwards Compatibility
	   
- `jsPlumb.addClass`, `jsPlumb.removeClass` and removed `jsPlumb.hasClass` removed. You don't need these. You can use the methods from the underlying library.
- `makeTargets` method removed from jsPlumb. You can pass an array or selector to `makeTarget`.
- `makeSources` method removed from jsPlumb. You can pass an array or selector to `makeSource`.
- `jsPlumb.detach` no longer supports passing in two elements as arguments.  Use instead either 


`jsPlumb.detach({source:someDiv, target:someOtherDiv});`

or

`jsPlumb.select({source:someDiv, target:someOtherDiv}).detach();`

- `jsPlumbConnectionDetached` event, which was deprecated, has been removed. Use `connectionDetached`.
- `jsPlumbConnection` event, which was deprecated, has been removed. Use `connection`.
- `Endpoint.isConnectedTo` method removed.  it didnt work properly as it only checked for connections where the Endpoint was the source.
- Many places in jsPlumb that used to use library-specific selectors for elements now use pure DOM elements.  It is best to re-select any elements you are getting from a jsPlumb object, even if you supplied them as a selector, as jsPlumb will have unwrapped your selector into a DOM element.

<a name="new"></a>
### New Functionality
  	     
- `jsPlumb.setSuspendDrawing` returns the value of `suspendDrawing` _before_ the call was made.
- `Endpoint.setElement` works properly now.
 
<a name="issues"></a>
### Issues Fixed

- **27** - investigate why a new connection is created after drag          
- **37** - .addClass() not working - IE8
- **39** - problem about connectionDrag event
- **49** - Calling detachEveryConnection winds up calling repaintEverything once for each endpoint
- **51** - arrow overlay orientation at location 1 on flowchart connectors
- **54** - Memory Leak Issue
- **57** - DOMException while dragging endpoints
- **60** - flowchart connector start position wrong
- **63**  - Flowchart midpoint=0 is ignored 
- **65** - Uncaught exception in IE 8
- **69** - jsPlumb.detach(connection) is really slow with larger graphs
- **72** - Drag and drop connections fail to work correctly when using makeTarget
- **75** - changing continuous anchor is ignored
- **76** - jsPlumb doesn't work in XHTML documents         

<a name="misc"></a>
### Miscellaneous

Nothing to report.
