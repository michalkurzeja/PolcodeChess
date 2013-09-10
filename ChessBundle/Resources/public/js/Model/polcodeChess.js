var polcodeChess = angular.module('polcodeChess', []);

polcodeChess.config(function($interpolateProvider) {
	$interpolateProvider.startSymbol('{[{').endSymbol('}]}');
})

polcodeChess.controller('ChessboardCtrl', function($scope, $http, boardFactory) {
	
	$scope.game_id;
	$scope.pieces = [];
	$scope.board = [];

	$scope.setPieces = function(pieces) {
		$scope.pieces = pieces;
	}
	
	$scope.setBoard = function() {
		
		
		console.log($scope.board);
		console.log($scope.pieces);
		
		
	}

	$scope.init = function(game_id) {
		$scope.game_id = game_id;
		data = boardFactory.getBoardAndPieces($scope.game_id);
		$scope.board = data[0];
		$scope.pieces = data[1];
	}

});

polcodeChess.factory('boardFactory', function($http) {
	var pieces = null;
	var board = {};
	
	var factory = {};
	
	factory.getBoardAndPieces = function(game_id) {
		if(!pieces) {
			$http({method: 'GET', url: '../pieces/' + game_id, headers: {'Content-type': 'application/json'}})
				.success(function(data) {
					pieces = data;
					

					for(piece in pieces) {
						var color = pieces[piece].is_white ? 'White' : 'Black';
						
						board[ pieces[piece].rank ][ pieces[piece].file ] = pieces[piece].class + color;
					}
				}).error(function() { console.log('Error getting pieces!');	});
		}

		for(var i=1; i<=8; i++) {
			board[i] = {};
			for(var j=1; j<=8; j++) {
				board[i][j] = null;
			}
			//board[i] = [1 => null, 2 => null, 3 => null, 4 => null, 5 => null, 6 => null, 7 => null];
		}
		console.log(board);
		return [board, pieces];
	}
	
	return factory;
});

polcodeChess.filter('reverse', function() {
	return function(items) {
		return items.slice().reverse();
	}
});
