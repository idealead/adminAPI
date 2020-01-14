'use strict;'
$(function() {
  init();
});
var oneClick = false;

function init() {
  try {
    // loadGit('http://img.intech.gdinsight.com/a20190911hr/gifFile4/home.gif', 'section1Main', 'mainGif', true, function() {})

    animate()
    $('.positionBtn').off().on('click', function() {
      if (!oneClick) {
        oneClick = true;
        var index = $(this).attr('data-index')
        index = parseInt(index)
        $(this).css('opacity',.6)
        jump(index, true)
        MtaH5.clickStat(index) //统计
      }
    })
    $('.back').off().on('click', function() {
      backF()
    })
    // $(window).scroll(function(e) {
    //   var scroH = $(window).scrollTop();
    //   if (scroH > 300) { $('.back').css('opacity', 1) } else { $('.back').css('opacity', 0) }
    // });

    function animate() {
      setTimeout(function() {
        $('html').css('opacity', 1)
        getNum()
      }, 50)
    }

    function getNum() {
      var number = getQueryVariable('index');
      number = parseInt(number)
      if (number) {
        jump(number, false)
        // $(window).scroll(function(e) {
        //   var scroH = $(window).scrollTop();
        //   if (scroH > 300) { $('.back').css('opacity', 1) } else { $('.back').css('opacity', 0) }
        // });
      }
    }

    function getQueryVariable(variable) {
      var query = window.location.search.substring(1);
      var vars = query.split("&");
      for (var i = 0; i < vars.length; i++) {
        var pair = vars[i].split("=");
        if (pair[0] == variable) { return pair[1]; }
      }
      return (false);
    }

    function setImgSize(className) {
      let ratio = 0.623;
      let browser_w = document.body.clientWidth;
      let browser_h = document.body.clientHeight;
      let browser_ratio = browser_w / browser_h;
      if (ratio >= browser_ratio) {
        $("." + className).css('height', '100%')
      } else {
        $('.' + className).css('width', '100%')
      }
    }

    function loadGit(src, selfClassName, className, set, callBack) {
      var img = new Image();
      img.src = src + '?v2';
      img.className = selfClassName
      if (img.complete) { // 如果图片已经存在于浏览器缓存，直接调用回调函数
        $('.' + className).empty()
        $('.' + className).append(img)
        if (set) {
          setImgSize(selfClassName)
        }
        callBack()
        return; // 直接返回，不用再处理onload事件
      }
      img.onload = function() {
        $('.' + className).empty()
        $('.' + className).append(img)
        if (set) {
          setImgSize(selfClassName)
        }
        callBack()
      }
    }

    function backF() {
      $('.section1').css('opacity', 1)
      $('.section2').css('opacity', 0)
      $('html,body').scrollTop(0);
      setTimeout(function() {
        $('.section1').css('z-index', 2)
        $('.section2').css('z-index', 1)
        $('.section2').css('height', '100%')
        // $('html,body').animate({ scrollTop: '0px' }, 300);
        $('html,body,.section2').css('overflow-y', 'hidden')
      }, 700)
      _wxData = {
        shareTitle: 'A股上市公司因赛集团1月热招岗位',
        shareTimelineTitle: '加入因赛，让梦想进阶',
        shareDesc: "加入因赛，让梦想进阶",
        shareLink: 'http://dev.cyrd.gdinsight.com/html/a20190911hr/index.html?index=a',
        shareImgUrl: 'http://dev.cyrd.gdinsight.com/html/a20190911hr/shareIcon.jpg?v1',
      }
      _pWxShare.init();
    }

    function jump(index, initShare) {
      var partner = '';
      switch (index) {
        case 1:
          partner = "战略咨询类合伙人";
          break;
        case 2:
          partner = "策略类合伙人";
          break;
        case 3:
          partner = "创意类合伙人";
          break;
        case 4:
          partner = "文案总监";
          break;
        case 5:
          partner = "美术、设计类合伙人";
          break;
        case 6:
          partner = "视频创作类合伙人";
          break;
        case 7:
          partner = "社会化传播类合伙人";
          break;
        case 8:
          partner = "AI技术类合伙人";
          break;
        case 9:
          partner = "数字营销类合伙人";
          break;
        case 10:
          partner = "媒介类合伙人";
          break;
        case 11:
          partner = "经营类合伙人";
          break;
        case 12:
          partner = "管理人才";
          break;
      }
      loadGit("http://img.intech.gdinsight.com/a20190911hr/gifFile4/gif" + index + ".jpg?v1", 'section2Img', 'section2Gif', false, function() {
        $('.section1').css('opacity', 0)
        $('.section2').css('opacity', 1)
        setTimeout(function() {
          $('.positionBtn').css('opacity', 1)
          $('.section1').css('z-index', 1)
          $('.section2').css('z-index', 2)
          $('.section2').css('height', 'auto')
          $('html,body,.section2').css('overflow-y', 'auto')
          oneClick = false;
          _wxData = {
            shareTitle: '因赛集团寻找' + partner,
            shareTimelineTitle: '因赛集团1月热招岗位',
            shareDesc: "因赛集团1月热招岗位",
            shareLink: 'http://dev.cyrd.gdinsight.com/html/a20190911hr/index.html?index=' + index,
            shareImgUrl: 'http://dev.cyrd.gdinsight.com/html/a20190911hr/shareIcon.jpg?v1',
          }
          _pWxShare.init();
        }, 1000)
      })
      loadGit("http://img.intech.gdinsight.com/a20190911hr/gifFile4/gif" + index + ".gif?v1", 'section2Img', 'section2Gif', false, function() {})
      $('#section2MainText').attr('src', "http://img.intech.gdinsight.com/a20190911hr/gifFile5-text/textmsg" + index + "-1.jpg?v4")
      $('#section2BottomText').attr('src', "http://img.intech.gdinsight.com/a20190911hr/gifFile5-text/textmsg" + index + "-3.jpg?v3")
    }


  } catch (err) {
    let str = JSON.stringify(err);
    console.log(str)
  }
}