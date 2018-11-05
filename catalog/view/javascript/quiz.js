var Quiz = {};
Quiz.data = {
	1: {
		question: 'Let’s start with where the rug will live in your home. What room would you like to "rugify"?',
		options: [{
				id: 0,
				classes: 'item_quiz',
				img: 'Kitchen600.jpg',
				text: "One with lots of water",
				filters: {
					style: ["Runner", "Extra Small(under 4x6)", "Small(4x6 - under 6x9)"]
				}
			}, {
				id: 1,
				classes: 'item_quiz',
				img: 'Living Room-Dining Room600.jpg',
				text: "One with a bed, desk, couches, or table",
				filters: {
					size: ["Extra Small(under 4x6)", "Small(4x6 - under 6x9)", "Medium(6x9 - under 9x12)", "Large(9x12 or over)"]
				}
			}, {
				id: 2,
				classes: 'item_quiz',
				img: 'Outdoor600.jpg',
				text: "An outdoor space",
				filters: {
					style: ["Outdoor"]
				}
			}, {
				id: 3,
				classes: 'item_quiz',
				img: 'Kids Room600.jpg',
				text: "Something for kids",
				filters: {
					size: ["Extra Small(under 4x6)", "Small(4x6 - under 6x9)", "Medium(6x9 - under 9x12)"]
				}
			}, {
				id: 4,
				classes: 'item_quiz',
				img: 'Bedroom600.jpg',
				text: "A hallway or entry",
				filters: {
					size: ["Runner"]
				}
			}],
		cssClass: "nav nav-pills nav-justified"
	},
	2: {
		question: "Think about the feel of your home. Which room best fits your style?",
		options: [{
				id: 0,
				img: "lodge.jpg",
				filters: {
					style: ["Southwest"]
				}
			}, {
				id: 1,
				img: "modern.jpg",
				filters: {
					style: ["Modern"]
				}
			}, {
				id: 2,
				img: "traditional.jpg",
				filters: {
					style: ["Traditional", "Southwest", "Modern"]
				}
			}],
		cssClass: "nav nav-pills"
	},
	3: {
		question: "Color is important! Which of the following would you love to see in your perfect rug?",
		options: [{
				id: 0,
				img: "red.jpg",
				filters: {
					color: ["Reds & Wines"]
				}
			}, {
				id: 1,
				img: "yellow.jpg",
				filters: {
					color: ["Yellow & Gold"]
				}
			}, {
				id: 2,
				img: "blue.jpg",
				filters: {
					color: ["Blues"]
				}
			}, {
				id: 3,
				img: "green.jpg",
				filters: {
					color: ["Greens"]
				},
				classes: "quiz-answer-5-choice"
			}, {
				id: 4,
				img: "orange.jpg",
				filters: {
					color: ["Oranges"]
				}
			}],
		cssClass: "nav nav-pills"
	},
	4: {
		question: "Your style is unique. Choose the room that matches the look and feel of your home.",
		options: [{
				id: 0,
				img: "casual.jpg",
				filters: {
					style: ["Casual"]
				}
			}, {
				id: 1,
				img: "shag.jpg",
				filters: {
					style: ["Shag"]
				}
			}, {
				id: 2,
				img: "braided.jpg",
				filters: {
					style: ["Braided"]
				}
			}],
		cssClass: "nav nav-pills"
	},
	5: {
		question: "Rugs come in a multitude of colors, pick the palette that matches your style!",
		options: [{
				id: 0,
				img: "purple.jpg",
				filters: {
					color: ["Purples"]
				}
			}, {
				id: 1,
				img: "white.jpg",
				filters: {
					color: ["Ivories & Beiges"]
				}
			}, {
				id: 2,
				img: "pink.jpg",
				filters: {
					color: ["Pinks"]
				}
			}, {
				id: 3,
				img: "black.jpg",
				filters: {
					color: ["Blacks & Greys"]
				},
				classes: "quiz-answer-5-choice"
			}, {
				id: 4,
				img: "brown.jpg",
				filters: {
					color: ["Browns"]
				}
			}],
		cssClass: "nav nav-pills"
	}
}, Quiz.properties = {
	style: [],
	size: [],
	color: []
}, Quiz.URL = "index.php?route=product/search", Quiz.optionsWrapper = $(".options-wrapper"), Quiz.optionTemplate = $("#option-template").html(), Mustache.parse(Quiz.optionTemplate),
Quiz.ask = function(i) {
	var e = Quiz.data[i];
	e.questionID = i, $("#question").html(e.question);
	var t = Mustache.render(Quiz.optionTemplate, e);
	Quiz.optionsWrapper.html(t), Quiz.optionsWrapper.one("click", "a", function() {
		$(".title").hide(), Quiz.acceptAnswer(i, $(this).data("optionid"))
	})
},
Quiz.acceptAnswer = function(i, e) {
	var t = null;

	return $.each(Quiz.data[i].options, function(o, s) {
		return s.id == e ? void(t = Quiz.data[i].options[o].filters) : void 0
	}),console.log(t),
	null !== t && $.each(t, function(i, e) {
		Quiz.properties[i] = Quiz.properties[i].concat(e).unique()
	}), Quiz.continue(i)

}, Quiz.continue = function(i) {
	if (Quiz.optionsWrapper.hide(), void 0 !== Quiz.data[i + 1])
		Quiz.ask(i + 1);
	else {
		var e = [];
		var op_name = [];
		var op_parent =[];
		var ft = [];
		var type = null;
		var str = '';
		$.each(Quiz.properties, function(i, t) {
			type = i;
			for (var o = t.length - 1; o >= 0; o--)
				$.each(rugOptions[i], function(i, s) {
					if (i.indexOf(t[o]) > -1) {
						if (type == 'color') {
							str = i.split('&amp;');
							str.forEach(function(name) {
								op_name.push(name.trim());
							});
							ft.push(s);
						} else if (type == 'size') {
							op_parent.push(s);
						} else {
							e.push(s)
						}
					}
				})
		}),
				ft.length > 0 &&(Quiz.URL +='&filter_color_id='+ft),
				op_parent.length > 0 && (Quiz.URL +='&filter_size_id='+op_parent),
				op_name.length > 0 && (Quiz.URL += '&filter_color_name=' + op_name),
				// e.length > 0 && (Quiz.URL += "&option_id=" + e.unique().join() + '&quiz=true&find=true'), $("#question").html(""),
				e.length > 0 && (Quiz.URL += "&filter_style_id=" + e.unique().join() + '&quiz=true&find=true'), $("#question").html(""),

			location = Quiz.URL;
	}
	Quiz.optionsWrapper.fadeIn()
}, Array.prototype.unique = function() {
	for (var i = this.concat(), e = 0; e < i.length; ++e)
		for (var t = e + 1; t < i.length; ++t)
			i[e] === i[t] && i.splice(t--, 1);
	return i
}, Quiz.ask(1), Quiz.optionsWrapper.fadeIn();