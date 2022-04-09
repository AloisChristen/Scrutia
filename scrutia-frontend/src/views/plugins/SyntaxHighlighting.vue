<template>
  <div>
    <!-- Hero -->
    <base-page-heading title="Highlight.js" subtitle="Beautiful syntax highlighting to showcase your code.">
      <template #extra>
        <b-breadcrumb class="breadcrumb-alt">
          <b-breadcrumb-item href="javascript:void(0)">Plugins</b-breadcrumb-item>
          <b-breadcrumb-item active>Syntax Highlighting</b-breadcrumb-item>
        </b-breadcrumb>
      </template>
    </base-page-heading>
    <!-- END Hero -->

    <!-- Page Content -->
    <div class="content">
      <!-- HTML -->
      <base-block rounded title="HTML">
        <highlightjs language="xml" :code="codeHtml" />
      </base-block>
      <!-- END HTML -->

      <!-- CSS -->
      <base-block rounded title="CSS">
        <highlightjs language="css" :code="codeCss" />
      </base-block>
      <!-- END CSS -->

      <!-- SCSS -->
      <base-block rounded title="SCSS">
        <highlightjs language="scss" :code="codeScss" />
      </base-block>
      <!-- END SCSS -->

      <!-- JavaScript -->
      <base-block rounded title="JavaScript">
        <highlightjs language="javascript" :code="codeJavascript" />
      </base-block>
      <!-- END JavaScript -->

      <!-- PHP -->
      <base-block rounded title="PHP">
        <highlightjs language="php" :code="codePhp" />
      </base-block>
      <!-- END PHP -->

      <!-- Ruby -->
      <base-block rounded title="Ruby">
        <highlightjs language="ruby" :code="codeRuby" />
      </base-block>
      <!-- END Ruby -->

      <!-- Python -->
      <base-block rounded title="Python">
        <highlightjs language="python" :code="codePython" />
      </base-block>
      <!-- END Python -->

      <!-- JSON -->
      <base-block rounded title="JSON">
        <highlightjs language="json" :code="codeJson" />
      </base-block>
      <!-- END JSON -->
    </div>
    <!-- END Page Content -->
  </div>
</template>

<style lang="scss">
// Highlight.js
@import '~highlight.js/scss/atom-one-dark.scss';
</style>

<script>
// Vue Highlight.js, for more info and examples you can check out https://github.com/metachris/vue-highlightjs
import Vue from 'vue'
import hljs from 'highlight.js/lib/core';
import javascriptLang from 'highlight.js/lib/languages/javascript';
import jsonLang from 'highlight.js/lib/languages/json';
import pythonLang from 'highlight.js/lib/languages/python';
import rubyLang from 'highlight.js/lib/languages/ruby';
import phpLang from 'highlight.js/lib/languages/php';
import xmlLang from 'highlight.js/lib/languages/xml';
import cssLang from 'highlight.js/lib/languages/css';
import scssLang from 'highlight.js/lib/languages/scss';
import VueHighlightJS from '@highlightjs/vue-plugin';

// Register Languages
hljs.registerLanguage('javascript', javascriptLang);
hljs.registerLanguage('json', jsonLang);
hljs.registerLanguage('python', pythonLang);
hljs.registerLanguage('ruby', rubyLang);
hljs.registerLanguage('php', phpLang);
hljs.registerLanguage('xml', xmlLang);
hljs.registerLanguage('css', cssLang);
hljs.registerLanguage('scss', scssLang);

// Register Vue Highlight.js
Vue.use(VueHighlightJS)

export default {
  data () {
    return {
      codeHtml: `<!doctype html>
<html>
  <head>
    <meta charset="utf-8">

    <title>Title</title>
  </head>
  <body>
    <!-- Your content -->
  </body>
</html>`,
      codeCss: `/*
=================================================================
SECTION
=================================================================
*/

/* Sub section 1 */
.selector {

}

/* Sub section 2 */
.selector {

}

/*
=================================================================
SECTION
=================================================================
*/

/* Sub section 1 */
.selector {

}

/* Sub section 2 */
.selector {

}`,
      codeScss: `$font-stack: Helvetica, sans-serif;
$primary-color: #333;

body {
  font: 100% $font-stack;
  color: $primary-color;
}`,
      codeJavascript: `/*
 *  Document   : app.js
 *  Author     : pixelcave
 */

var App = function () {

  // User Interface init
  var uiInit = function () {

  };

  return {
    init: function () {
      uiInit();
    }
  };
}();

// Initialize app when page loads
jQuery(function(){ App.init(); });`,
      codePhp: `<?php
class App {
  function home ()
  {
    // ...
  }

  function profile ()
  {
    // ...
  }

  function settings ()
  {
    // ...
  }
}`,
      codeRuby: `# Output "I love Ruby"
say = "I love Ruby"
puts say

# Output "I *LOVE* RUBY"
say['love'] = "*love*"
puts say.upcase

# Output "I *love* Ruby"
# five times
5.times { puts say }`,
      codePython: `name = raw_input('What is your name?\n')

print 'Hi, %s.' % name`,
      codeJson: `{
  "menu": {
    "id": "file",
    "value": "File",
    "popup": {
      "menuitem": [
        {"value": "New", "onclick": "CreateNewDoc()"},
        {"value": "Open", "onclick": "OpenDoc()"},
        {"value": "Close", "onclick": "CloseDoc()"}
      ]
    }
  }
}`,
    }
  }
}
</script>