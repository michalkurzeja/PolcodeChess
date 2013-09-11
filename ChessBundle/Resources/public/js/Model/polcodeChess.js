var polcodeChess = angular.module('polcodeChess', []);

polcodeChess.config(function($interpolateProvider) {
	$interpolateProvider.startSymbol('{[{').endSymbol('}]}');
})

polcodeChess.controller('ChessboardCtrl', function($scope, $http, boardFactory) {
	
	$scope.game_id;
	$scope.player_color;
	$scope.board = [];
	
	var $chessboard;
	var highlight = [];
	var selection_highlight = [];
	var selection = null;
	var turn = false;

	$scope.highlightMovesOn = function(piece) {
		if(turn) {
			if( piece.is_white == $scope.player_white ) {
				for(square in piece.moves) {
					highlight[square] = $chessboard
						.find("[data-coords='" + piece.moves[square].x + piece.moves[square].y + "']");
						
					highlight[square].addClass('move-highlight');
				}
			}
		}
	}
	
	$scope.highlightMovesOff = function() {
		for(square in highlight) {
			highlight[square].removeClass('move-highlight');
		}
		highlight = [];
	}
	
	$scope.squareClicked = function(file, rank) {
		if(turn) {
			target = $scope.board[ rank-1 ][ file-1 ];
			console.log(target);
			if( !selection ) {
				/* click on empty square (clear selection) */
				if( !target ) {
					clearSelection();
					return;
				}
				
				/* click on a piece of your color (select) */
				if( target.is_white == $scope.player_white ) {
					select(target);
					return;
				}
				
				return;
			}
			
			/* click on selected square */
			if( selection.file == file && selection.rank == rank) {
				clearSelection();
				return;
			} 
			
			/* click on another piece of the same color (switch selection) */
			if( target && target.is_white == $scope.player_white ) {
				clearSelection();
				select(target);
				return;
			}
			
			/* click on disallowed square with piece selected */
			if( !isMoveLegal( {x: file, y: rank}, selection ) ) {
				clearSelection();
				return;
			}
			
			movePiece(selection, {x: file, y: rank});
			clearSelection();
		}
	}
	
	$scope.init = function(game_id, player_white) {
		$scope.game_id = game_id;
		$scope.player_white = player_white;
		$scope.board = boardFactory.getBoardAndPieces($scope.game_id);
		$chessboard = $('#chessboard');
	}
	
	function movePiece(piece, coords) {
		console.log('moving [' + piece.file + ', ' + piece.rank + '] to [' + coords.x + ', ' + coords.y + ']');
		$scope.board[ coords.y - 1 ][ coords.x - 1 ] = piece;
		$scope.board[ piece.rank - 1 ][ piece.file - 1 ] = null;
		
		turn = false;
	}
	
	function isMoveLegal(coords, piece) {
		for(square in piece.moves) {
			if(piece.moves[square].x == coords.x && piece.moves[square].y == coords.y) {
				console.log('isMoveLegal() == true');
				return true;
			}
		}

		console.log('isMoveLegal() == false');		
		return false;
	}
	
	function select(piece) {
		selection = piece;
		
		$selection = $chessboard.find("[data-coords='" + piece.file + piece.rank + "']");
		$selection.addClass('square-selected');

		selectionHighlight( piece );	
	}
	
	function clearSelection() {
		clearSelectionHighlight();
		if(selection) {
			$selection = $chessboard.find("[data-coords='" + selection.file + selection.rank + "']");
			$selection.removeClass('square-selected');
			
			selection = null;
		}
	}

	function selectionHighlight(piece) {
		for(square in piece.moves) {
			selection_highlight[square] = $chessboard
				.find("[data-coords='" + piece.moves[square].x + piece.moves[square].y + "']");
				
			selection_highlight[square].addClass('selection-highlight');
		}
	}
		
	function clearSelectionHighlight() {
		for(square in selection_highlight) {
			selection_highlight[square].removeClass('selection-highlight');
		}
		selection_highlight = [];
	}

});

polcodeChess.factory('boardFactory', function($http) {
	var board = [];
	
	var factory = [];
	
	factory.getBoardAndPieces = function(game_id) {
		for(var i=0; i<8; i++) {
			board[i] = [];
			for(var j=0; j<8; j++) {
				board[i][j] = null;
			}
		}
		
		$http({method: 'GET', url:  game_id + '/pieces', headers: {'Content-type': 'application/json'}})
			.success(function(data) {
				for(piece in data) {
					var color = data[piece].is_white ? 'White' : 'Black';
					
					board[ data[piece].rank - 1 ][ data[piece].file - 1 ] = {
																					id: piece,
																					classname: data[piece].classname,
																					name: data[piece].classname + color,
																					file: data[piece].file,
																					rank: data[piece].rank,
																					is_white: data[piece].is_white,
																					moves: data[piece].moves																			
																				};
				}
			}).error(function() { console.log('Error getting pieces!');	});
	
		return board;
	}
	
	return factory;
});

polcodeChess.filter('reverse', function() {
	return function(items) {
		return items.slice().reverse();
	}
});
