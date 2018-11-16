/*
(function($) {
    jQuery('.wpProQuiz_content').on('learndash-quiz-answer-response-contentchanged', function(e) {
        if ( typeof MathJax !== 'undefined' ) {
            MathJax.Hub.Queue(["Typeset", MathJax.Hub]);
            e.stopPropagation();
        }
    });

    jQuery('.wpProQuiz_content').on('learndash-quiz-init', function(e) {
        if ( typeof MathJax !== 'undefined' ) {
            MathJax.Hub.Queue(["Typeset", MathJax.Hub]);
        }
    });
})( jQuery ); */