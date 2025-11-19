import pyshark
import argparse

parser = argparse.ArgumentParser()
parser.add_argument('pcapng_file')

keycode_map = {
    4: ['a', 'A'], 5: ['b', 'B'], 6: ['c', 'C'], 7: ['d', 'D'],
    8: ['e', 'E'], 9: ['f', 'F'], 10: ['g', 'G'], 11: ['h', 'H'],
    12: ['i', 'I'], 13: ['j', 'J'], 14: ['k', 'K'], 15: ['l', 'L'],
    16: ['m', 'M'], 17: ['n', 'N'], 18: ['o', 'O'], 19: ['p', 'P'],
    20: ['q', 'Q'], 21: ['r', 'R'], 22: ['s', 'S'], 23: ['t', 'T'],
    24: ['u', 'U'], 25: ['v', 'V'], 26: ['w', 'W'], 27: ['x', 'X'],
    28: ['y', 'Y'], 29: ['z', 'Z'], 30: ['1', '!'], 31: ['2', '@'],
    32: ['3', '#'], 33: ['4', '$'], 34: ['5', '%'], 35: ['6', '^'],
    36: ['7', '&'], 37: ['8', '*'], 38: ['9', '('], 39: ['0', ')'],
    40: ['\n', None], 44: [' ', None], 45: ['-', '_'], 46: ['=', '+'],
    47: ['[', '{'], 48: [']', '}'], 49: ['\\', '|'], 51: [';', ':'],
    52: ["'", '"'], 54: [',', '<'], 55: ['.', '>'], 56: ['/', '?'],
    0: [None, None]
}

args = parser.parse_args()

cap = pyshark.FileCapture(args.pcapng_file)

message = ""

for pkt in cap:
    transfer_type = int(pkt.usb.transfer_type, 16)
    if transfer_type != 1: continue
    data = pkt.DATA.usbhid_data.binary_value
    shift = 1 if data[1] == 2 else 0
    char = data[3]
    key = keycode_map[char][shift]
    if key: message += key

print(message)
