jQuery(document).ready(function ($) {

    function siw_save_score(){
        $('#main-toggles').ajax({
            success: function () {
                console.log("Succes");
                setTimeout(function () {
                    // $checkbox.parent().parent().removeClass("updated");
                }, 2500);
            },
            timeout: 5000
        });
    }

    function loadPageSpeed(strat) {
        let strategy = strat ? strat : '';
        console.log("Getting: " + strategy);

        $(".page_speed_result").addClass("page_speed_result-loading");
        const resultBox = document.getElementById('page_speed_result');
        resultBox.innerHTML = '<div></div>';
        const key = window.atob("QUl6YVN5Qk1uRTRyTHd3TWN1WFJzc2MwVUk3ejJaWENrT0lXcVd3");
        $.ajax({
            url: 'https://www.googleapis.com/pagespeedonline/v5/runPagespeed?url=https://'+window.location.hostname+'&key='+key+strategy,
            type: 'GET',
            data: {},
            async: true,
            dataType: 'json',
            success: function (data) {
                $(".page_speed_result").removeClass("page_speed_result-loading");
                let totalScore = 0;
                resultBox.innerHTML = '<div id="chartContainer">' +
                    '<h3 id="chartTitle"></h3>' +
                    '<canvas id="totalScoreChart"></canvas>' +
                    '</div>' +
                    '<h3>Lab data</h3>' +
                    '<table id="resultTable">' +
                    '</table>'+
                    '<div id="finalScreenshot"></div>' +
                    '<div id="speedPics"></div>' +
                    '</div>';
                const types = data.lighthouseResult.audits;
                const ewl = ['first-contentful-paint', 'speed-index', 'interactive', 'first-meaningful-paint', 'first-cpu-idle', 'estimated-input-latency'];
                let i = 0;
                let xi = 0;
                for (let key in types){
                    if (typeof types[key] !== 'function') {
                        if(types[key].score == null) {
                            types[key].score = 0;
                        }
                        if(ewl.includes(key)) {
                            let score = (types[key].score * 100);
                            totalScore = totalScore + score;
                            i++;
                        }
                        if (typeof types[key].score !== 'undefined' && types[key].score !== null && typeof types[key].displayValue !== 'undefined') {
                            createChart(types[key]);
                            xi++;
                        }
                    }
                }
                totalScore = Math.round(totalScore / i);
                document.getElementById('chartTitle').innerText = 'Totalscore: ' + totalScore + '%';
                new Chart(document.getElementById('totalScoreChart'), {
                    type: 'doughnut',
                    data: {
                        labels: ['Total score', ''],
                        datasets: [{
                            label: 'Total score',
                            data: [totalScore, 100 - totalScore],
                            backgroundColor: [
                                chartColor(totalScore),
                                'rgba(54, 162, 235, 0)',
                            ],
                            borderColor: [
                                chartColor(totalScore),
                                'rgba(54, 162, 235, 0)',
                            ],
                            borderWidth: 0
                        }]
                    }
                });
                document.getElementById('finalScreenshot').innerHTML = '<img src="'+ types['final-screenshot'].details.data +'" alt=""><p>Final Screenshot</p>';
                const pictureData = types['screenshot-thumbnails'].details.items;
                for (let i = 0; i < pictureData.length; i++) {
                    document.getElementById('speedPics').innerHTML += '<div class="speedPic"">' +
                        '<img src="'+ pictureData[i].data +'" alt="'+ pictureData[i].timing +'">' +
                        '<p>'+ pictureData[i].timing +'ms</p>' +
                        '</div>';
                }

            }, error: function (data) {
                resultBox.innerHTML = ' status : ' + data.status + ' statusText : ' + data.statusText + ' readyState : ' + data.readyState + ' responseText : ' + data.responseText;
            },
            timeout: 0
        });
    }
    $(".siw_pagespeed_btn").on("click", function(){
        loadPageSpeed('&strategy='+$(this).data("value"));
    });

    function createChart(data) {
        const score = data.score * 100;
        let headings = '';
        let headingKeys = [];
        let rows = '';
        if(typeof data.details !== 'undefined' && data.details.type === 'opportunity') {
            rows += '<tr class="showDetails"><td><span class="dashicons dashicons-arrow-up"></span></td></tr>';
            for(let i = 0; i < data.details.headings.length; i++) {
                if(data.details.headings[i].valueType !== "thumbnail") {
                    if(data.details.headings[i].key === 'url') {
                        const key = data.details.headings[i].key;
                        const title = data.details.headings[i].label;
                        headings += '<th class="itemHeading">' + title + '</th>';
                    }
                } else {

                }
            }
            for(let z = 0; z < data.details.items.length; z++){
                let rowColums = '';
                for(let headKey in data.details.items[z]) {
                    if(headKey === 'url') {
                        rowColums += '<td class="itemUrl"><a target="_blank" href="'+ data.details.items[z]['url'] +'">' + data.details.items[z]['url'] + '</a></td>';
                    }
                }
                rows += '<tr class="detailUrls" style="display: none;">'+ rowColums +'</tr>';
            }
        }
        document.getElementById('resultTable').innerHTML += '<tr>' +
            '<td class="result">'+ data.title +':</td>' +
            '<td class="result" style="color: '+ chartColor(score) +'">'+ data.displayValue +'</td></tr>' +
            rows;
        return score;
    }

    function chartColor(score) {
        if(score >= 90) {
            return 'green';
        } else if(score >= 50 && score <= 89) {
            return 'orange';
        } else if(score >= 0 && score <= 49) {
            return 'red';
        }
    }

    $('#page_speed_result').on("click", ".showDetails", function(){
        $(this).toggleClass('open');
        $(this).siblings('.detailUrls').slideToggle(1000);
    });
});
