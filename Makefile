include Makefile.h

backup: FORCE
	$(CMD) $(RM) ..$(PATHSEP)rabbitmq.tar.gz
	$(CMD) $(MV) vendor ..$(PATHSEP)vendor
	$(CMD) $(CD) .. $(CHAIN) $(TAR) -cv --file=rabbitmq.tar rabbitmq/
	$(CMD) $(MV) ..$(PATHSEP)vendor .$(PATHSEP)vendor
	$(CMD) $(CD) .. $(CHAIN) $(GZIP) rabbitmq.tar

