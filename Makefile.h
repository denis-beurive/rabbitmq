ifeq ($(OS),Windows_NT)
	MV              = move
	RM              = del /F /Q
	CD              = CHDIR
	CHAIN           = \&\&
	PATHSEP2        = \\
	CMD             = cmd /C

	PHPDOC     = vendor\bin\phpdoc.bat
	PHPUNIT    = vendor\bin\phpunit.bat
	COMPOSER   = composer
	TAR        = tar
	GZIP       = gzip
else
	MV              = mv
	RM              = rm -f
	CD              = cd
	CHAIN           = &&
	PATHSEP2        = /
	CMD             =

	PHPDOC     = phpdoc
	PHPUNIT    = phpunit
	COMPOSER   = composer
	TAR        = tar
	GZIP       = gzip
endif

PATHSEP = $(strip $(PATHSEP2))

info:
	echo $(OS)
	echo $(PATHSEP)
	echo $(CMD)

FORCE:
