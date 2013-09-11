var polcodeChess = angular.module('polcodeChess', []);

polcodeChess.config(function($interpolateProvider) {
	$interpolateProvider.startSymbol('{[{').endSymbol('}]}');
})

polcodeChess.controller('ChessboardCtrl', function($scope, $http, boardFactory) {
	
	$scope.game_id;
	$scope.pieces = [];
	$scope.board = [];
	
	var $chessboard;
	var highlight = [];

	$scope.highlightMovesOn = function(piece) {
		for(square in $scope.pieces[piece.id].moves) {
			highlight[square] = $chessboard
				.find("[data-coords='" + $scope.pieces[piece.id].moves[square].x + $scope.pieces[piece.id].moves[square].y + "']");
				
			highlight[square].addClass('move-highlight');
		}
	}
	
	$scope.highlightMovesOff = function() {
		for(square in highlight) {
			highlight[square].removeClass('move-highlight');
		}
	}
	
	$scope.init = function(game_id) {
		$scope.game_id = game_id;
		data = boardFactory.getBoardAndPieces($scope.game_id);
		$scope.board = data[0];
		$scope.pieces = data[1];
		$chessboard = $('#chessboard');
	}

});

polcodeChess.factory('boardFactory', function($http) {
	var pieces = [];
	var board = [];
	
	var factory = [];
	
	factory.getBoardAndPieces = function(game_id) {
		
		pieces = [];
		
		for(var i=0; i<8; i++) {
			board[i] = [];
			for(var j=0; j<8; j++) {
				board[i][j] = null;
			}
		}
		
		$http({method: 'GET', url:  game_id + '/pieces', headers: {'Content-type': 'application/json'}})
			.success(function(data) {
				for(piece in data) {
					pieces[piece] = data[piece];
				}
				
				for(piece in pieces) {
					var color = pieces[piece].is_white ? 'White' : 'Black';
					
					board[ pieces[piece].rank - 1 ][ pieces[piece].file - 1 ] = {
																					name: pieces[piece].class + color,
																					id: piece,
																					x: pieces[piece].file,
																					y: pieces[piece].rank																			
																				};
				}
			}).error(function() { console.log('Error getting pieces!');	});
	
		return [board, pieces];
	}
	
	return factory;
});

polcodeChess.filter('reverse', function() {
	return function(items) {
		return items.slice().reverse();
	}
});
