from utils import create_chalenge_capturefile

with open('base_text.txt') as file:
    base_text = file.read()

with open('flag.txt') as file:
    flag = file.read()

create_chalenge_capturefile(base_text, flag, 'challenge.pcapng')