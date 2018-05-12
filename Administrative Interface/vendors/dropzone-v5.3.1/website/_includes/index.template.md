---
layout: default
title: Dropzone.js
---

<section markdown="1">

Try it out!
===========

<div id="dropzone"><form action="/upload" class="dropzone needsclick" id="demo-upload">

  <div class="dz-message needsclick">
    Drop files here or click to upload.<br>
    <span class="note needsclick">(This is just a demo dropzone. Selected files are <strong>not</strong> actually uploaded.)</span>
  </div>

</form></div>

</section>



<section class="news" markdown="1">

News
====

{% comment %}
{% include _for_hire.html %}
{% endcomment %}

**Dropzone 5.2.0**

The most requested feature of this release is **chunked uploads**! Thanks to
**SIMPEL** for donating the money, making this feature available to everybody!
Check out [the wiki on chunked uploads](https://gitlab.com/meno/dropzone/wikis/faq#chunked-uploads) for more
information and see the [full CHANGELOG on GitLab](https://gitlab.com/meno/dropzone/blob/master/CHANGELOG.md).

<p style="text-align: center;">
  <a href="http://simpel.com.au/" target="_blank" rel="nofollow noopener" style="border-bottom: none; background: white; padding: 0.8rem 1rem 0.5rem 0.9rem; display: inline-block;">
    <img src="https://i.imgur.com/hih5Bka.jpg" alt="SIMPEL Company Logo" style="width: 240px; height: 53px">
  </a>
</p>

**Dropzone 5.0.0**

Big shout out to MD Systems, who donated the money to make **browser side image resizing** available to everybody!

<p style="text-align: center;">
  <a href="https://www.md-systems.ch/" target="_blank" rel="nofollow noopener" style="border-bottom: none;"><svg style="width: 12em; height: 1.52em;" xmlns="http://www.w3.org/2000/svg" width="1708.859" height="219.627" viewBox="0 0 1708.859 219.627"><filter id="a" color-interpolation-filters="sRGB"><feFlood flood-color="#000" flood-opacity=".294" result="flood"/><feComposite in="flood" in2="SourceGraphic" operator="in" result="composite1"/><feGaussianBlur in="composite1" stdDeviation="2" result="blur"/><feOffset dy="1" dx="2" result="offset"/><feComposite in="SourceGraphic" in2="offset" result="composite2"/></filter><g filter="url(#a)" transform="translate(734.785 -221.68)"><path fill="#A7A48B" d="M-715.679 440.109c-5.528-1.764-11.9-7.242-14.618-12.568-2.375-4.654-2.488-7.432-2.488-61.242 0-63.427.308-66.375 8.855-84.787 15.099-32.524 41.864-52.731 77.621-58.6 29.471-4.838 63.47 4.839 87.3 24.847l8.917 7.487 6.466-6.086c12.343-11.62 24.323-18.402 41.707-23.612 9.2-2.757 13.577-3.318 26.634-3.413 8.792-.063 18.905.61 23.125 1.54 38.608 8.507 69.725 37.049 80.802 74.115 2.496 8.354 2.78 12.558 3.261 48.212l.526 39.062h54.697c51.482 0 55.125-.146 61.979-2.506 10.666-3.67 16.562-7.53 25.123-16.456 8.44-8.797 11.692-14.333 14.897-25.356 7.181-24.702-8.288-50.583-36.391-60.881-6.707-2.458-10.672-2.78-42.396-3.443-37.546-.785-39.016-1.039-45.167-7.8-5.263-5.786-7.006-10.96-6.479-19.231.68-10.675 5.021-17.184 14.296-21.43l6.843-3.133 39.003.544c34.835.486 40.041.828 48.72 3.2 19.901 5.439 40.812 18.87 53.457 34.337 7.81 9.55 17.905 29.772 20.734 41.531 3.1 12.881 2.778 36.994-.662 50-9.649 36.459-36.751 66.275-70.746 77.838l-10 3.401-78.75.396c-54.225.272-80.192-.027-83.381-.966-6.253-1.837-12.116-7.001-14.895-13.118-2.088-4.598-2.297-9.098-2.322-50.101-.021-35.831-.409-46.515-1.892-52.208-5.226-20.063-20.479-36.686-39.227-42.748-9.841-3.182-23.877-2.15-33.271 2.446-12.672 6.2-24.576 20.861-29.482 36.311-1.123 3.537-1.799 20.214-2.309 56.983l-.721 51.986-3.579 5.159c-4.019 5.795-14 11.498-20.104 11.486-11.207-.021-22.799-8.244-25.541-18.121-.902-3.25-1.389-21.44-1.394-52.054-.01-43.228-.207-47.7-2.462-55-4.725-15.289-16.781-29.366-31.186-36.415-7.605-3.722-9.188-4.029-20.734-4.029-11.095 0-13.283.385-19.468 3.425-12.872 6.325-23.918 19.567-28.945 34.7-2.536 7.634-2.741 11.236-3.393 59.584l-.693 51.457-3.75 5.307c-2.062 2.918-5.924 6.428-8.582 7.801-5.464 2.827-14.694 3.82-19.935 2.149z"/><path d="M-103.635 432.574c-23.302-6.107-33.929-15.818-28.596-26.132 3.938-7.614 8.184-8.028 20.875-2.042 12.13 5.721 22.076 8.164 33.235 8.164 10.315 0 20.681-3.062 27.208-8.041 6.044-4.609 10.629-14.625 10.596-23.143-.062-15.609-7.895-23.741-35.604-36.965-10.54-5.029-22.162-11.067-25.828-13.417-9.293-5.958-17.481-14.654-21.771-23.119-3.444-6.802-3.644-8.034-3.644-22.815 0-14.714.205-16.014 3.516-22.3 9.975-18.935 28.812-28.906 54.609-28.906 17.067 0 38.384 6.544 43.817 13.451 3.519 4.473 3.282 12.77-.462 16.288-3.74 3.516-8.269 3.438-15.586-.268-26.516-13.427-54.485-7.136-59.057 13.281-1.28 5.721-1.201 7.861.508 13.738 3.211 11.048 10.673 16.923 39.736 31.287 17.666 8.73 26.037 13.628 31.123 18.207 13.028 11.728 18.47 27.485 16.074 46.561-2.877 22.912-19.45 40.435-44.231 46.761-10.729 2.738-34.991 2.431-46.518-.59zm284.948.088c-25.212-6.195-36.839-18.801-26.694-28.941 4.146-4.146 9.198-4.77 13.615-1.676 1.557 1.091 7.445 3.686 13.092 5.767 22.89 8.438 46.791 5.067 57.115-8.062 3.419-4.345 6.273-12.676 6.267-18.278-.024-15.769-7.575-23.386-37.691-38.022-25.845-12.562-35.006-19.006-42.078-29.596-6.689-10.02-8.238-15.623-8.13-29.412.114-14.388 3.255-23.068 11.681-32.287 10.899-11.927 27.229-18.304 46.854-18.298 19.068.006 41.045 7.313 45.001 14.962 2.812 5.438 2.362 10.873-1.194 14.43-4.116 4.116-6.834 3.889-19.381-1.62-10.115-4.441-11.365-4.688-23.801-4.688-11.054 0-13.914.425-18.125 2.691-6.672 3.591-10.146 7.513-12.458 14.064-2.529 7.169-2.383 13.349.466 19.623 4.303 9.474 11.368 14.631 38.338 27.984 28.763 14.24 37.123 20.812 43.745 34.388 3.429 7.024 3.658 8.451 3.658 22.563 0 14.896-.053 15.173-4.698 24.572-7.628 15.435-20.754 25.639-39.002 30.324-10.37 2.662-34.804 2.406-46.58-.488zm704.023 1.567c-17.012-3.708-31.203-10.575-34.946-16.91-2.852-4.827-1.683-11.284 2.661-14.7 4.054-3.188 8.638-3.244 13.688-.166 8.156 4.976 22.148 9.115 33.041 9.778 24.587 1.498 40.353-9.142 42.1-28.409 1.553-17.119-5.909-25.141-37.757-40.588-29.539-14.328-40.33-23.102-46.463-37.779-3.162-7.568-4.372-19.587-2.9-28.801 4.259-26.659 26.394-42.796 58.704-42.796 16.924 0 36.42 5.965 43.116 13.192 6.225 6.715 2.479 19.264-5.75 19.264-2.034 0-6.377-1.356-9.649-3.015-10.547-5.345-19.63-7.332-30.728-6.722-11.72.644-18.916 3.703-23.619 10.038-9.045 12.182-7.687 24.624 3.935 35.991 5.885 5.758 11.212 8.962 31.783 19.119 27.53 13.593 35.987 20.163 42.927 33.353 3.442 6.545 3.61 18.773 3.61 23.111s-.068 16.385-4.257 24.732c-7.38 14.707-21.685 25.668-39.443 30.229-6.992 1.795-33.492 2.51-40.053 1.079zM61.92 430.168l-4.082-3.646-.625-36.042-.625-36.042-26.213-52.185L4.163 250.07 6 244.754c3.281-9.493 14.681-12.357 21.396-5.377 2.797 2.905 28.138 54.872 37.517 76.937 2.774 6.531 5.438 12.293 5.915 12.804.477.51 3.055-4.271 5.729-10.625 10.123-24.06 35.92-76.658 38.925-79.366 3.729-3.361 12.228-3.828 16.404-.9 1.501 1.051 3.295 3.958 3.985 6.458 1.628 5.898-.583 10.97-30.146 69.13L83.172 358.19l.888 30.875c.644 22.312.483 32.099-.568 35.292-3.209 9.712-13.961 12.609-21.572 5.811zm290.243 1.819c-6.311-3.433-6.21-2.063-6.527-90.048l-.299-82.5-26.821-.338c-25.171-.317-26.992-.498-29.582-2.931-3.64-3.419-4.715-8.147-2.867-12.607 3.026-7.307 2.438-7.249 73.487-7.249h64.966l3.849 3.236c5.061 4.258 5.409 11.562.775 16.196-3.065 3.068-3.071 3.068-30 3.068h-26.932v166.5l-4.25 4.25c-4.706 4.706-10.086 5.531-15.799 2.423zm258.798.227c-1.719-.857-4.114-3.521-5.323-5.918-2.047-4.059-2.177-10.562-1.875-94.153l.323-89.796 3.652-3.142c3.53-3.037 4.136-3.125 18.125-2.657 15.52.52 20.854 2.194 24.441 7.671 1.028 1.569 14.856 35.724 30.729 75.9 15.872 40.175 28.981 73.169 29.134 73.32s13.749-32.439 30.22-72.424c16.471-39.985 31.083-74.414 32.471-76.512 4.237-6.399 9.5-8.185 24.136-8.187 12.603-.002 12.996.085 16.562 3.652l3.654 3.654v181.692l-4.25 4.25c-3.062 3.061-5.368 4.25-8.245 4.25-4.41 0-10.636-3.168-12.153-6.184-.537-1.068-1.258-39.166-1.602-84.663l-.625-82.722-34.254 84.973c-23.298 57.791-35.082 85.552-36.841 86.783-3.061 2.143-15.545 2.436-19.865.467-2.485-1.133-8.082-14.666-35.371-85.538l-32.419-84.193-.625 80.851c-.479 61.703-.997 81.504-2.197 83.602-3.556 6.215-11.136 8.356-17.802 5.024zm-151.423-2.041l-2.951-2.389-.337-91.043c-.238-64.342.066-92.021 1.045-94.38 2.453-5.922 4.758-6.165 54.768-5.782l46.618.357 3.018 3.507c3.282 3.82 3.836 8.242 1.562 12.513-3.039 5.705-3.541 5.773-42.607 5.815l-37.188.04v61.521l31.771-.448 31.771-.448 3.229 3.03c4.402 4.131 4.479 11.461.162 15.775l-3.067 3.068h-63.866v68.646l38.234.361 38.233.365 3.017 3.507c3.282 3.819 3.836 8.242 1.562 12.515-3.088 5.797-2.871 5.771-53.72 5.813-45.726.046-48.461-.079-51.254-2.343z"/></g></svg></a>
</p>


</section>

<section markdown="1">

# New Video Out Now!

<div>
  <div class="embedded-video">
    <iframe style="margin: 0 auto;" width="560" height="315" src="https://www.youtube.com/embed/2McZErtMST8?rel=0&amp;showinfo=0" frameborder="0" allowfullscreen></iframe>
  </div>
</div>

**Previous Videos**

<div class="video-grid">
  <div class="video-grid__cell">
    <iframe style="margin: 0 auto;" width="280" height="158" src="https://www.youtube.com/embed/z-OUBkuDzv4?rel=0&amp;showinfo=0" frameborder="0" allowfullscreen></iframe>
  </div>
  <div class="video-grid__cell">
    <iframe style="margin: 0 auto;" width="280" height="158" src="https://www.youtube.com/embed/sFBFkZYGgcE?rel=0&amp;showinfo=0" frameborder="0" allowfullscreen></iframe>
  </div>
</div>


<div>
  <br>
  For the full desktop experience, visit <a href="http://www.meno.fm/flashlights">www.meno.fm/flashlights</a> —
  I wrote a <a href="http://www.colorglare.com/2016/02/05/flashlights.html"><strong>COLORGLARE</strong> post about the build process</a>. 
    
  To keep updated, like my <a href="https://www.facebook.com/thisismeno/">Facebook artist page</a>! <div style="display: inline-block; position: relative; top: -0.2em; margin-left: 0.4em;"><div class="fb-like" data-href="https://www.facebook.com/thisismeno/" data-layout="button" data-action="like" data-show-faces="false" data-share="false" data-colorscheme="dark"></div></div>
    
  Purchase my EP on
  <a href="https://play.google.com/store/music/album/Meno_Flashlights?id=Bvkm477idlkjw6joacowb7aa4he">Google Play</a>,
  <a href="https://www.amazon.com/gp/product/B01AP3ETYO?ie=UTF8&keywords=meno%20flashlights&qid=1454067033&ref_=sr_1_3&s=dmusic&sr=8-3">Amazon Music</a>
  or <a href="https://itunes.apple.com/at/album/flashlights-ep/id1075875101?l=en">iTunes</a> –
  or stream it on <a href="https://open.spotify.com/album/14y7LCmuPCBAZqrvc6uqkd">Spotify</a>:
  <br><br>
  <iframe style="display: block; width: 280px; margin: 0 auto;" src="https://embed.spotify.com/?uri=spotify%3Aalbum%3A14y7LCmuPCBAZqrvc6uqkd&theme=white&view=coverart" width="280" height="80" frameborder="0" allowtransparency="true"></iframe>
</div>


<!--Dropzone **v4.0.0** is released! It has been completely redesigned, and
the website has been updated. Big thanks to [1910](http://www.weare1910.com)
for designing the new logo and website. It looks fantastic. Check out their 
work!-->


</section>


{{ generated_readme_content }}


<section markdown="1">

Donate
======

Please consider donating if you like this project. I’ve put a lot of my free
time into this project and donations help to justify it.


<div>
Use the Paypal

<form class="donate" action="https://www.paypal.com/cgi-bin/webscr" method="post" target="_top">
<input type="hidden" name="cmd" value="_s-xclick">
<input type="hidden" name="hosted_button_id" value="CA598M5X362GQ">
<input type="image" src="https://www.paypalobjects.com/en_US/i/btn/btn_donate_LG.gif" border="0" name="submit" alt="PayPal - The safer, easier way to pay online!">
<img alt="" border="0" src="https://www.paypalobjects.com/de_DE/i/scr/pixel.gif" width="1" height="1">
</form>

button, <a href="http://tiptheweb.org/">tiptheweb</a> or my
<a href="http://bitcoin.org/">Bitcoin</a> address:
<div class="bitcoin"><code>19k17pTRGS1ykZaL7Qeju2HgXnoPXceQme</code></div>.
</div>

</section>

